# Enable mail on the server

Owner notifications for Contact Messages and Commission Requests are queued and sent by Horizon. Submissions are always saved to admin Messages, whether or not mail works.

The mail provider isn't chosen yet. These steps use SMTP, which every common provider offers and which needs no extra packages. Provider API transports (`resend`, `ses`, `postmark`) need a Composer package that isn't installed, so they require a code change first.

## 1. Set up the provider

1. Create an account with a transactional mail provider and add the sending domain.
2. Add the DNS records it asks for (SPF and DKIM), and wait until it shows the domain as verified. Mail from an unverified domain is rejected or lands in spam.
3. Copy its SMTP host, port, username and password.

## 2. Edit the server's `.env`

The live file is `$APP_ROOT/shared/.env`, which every release links to. `$APP_ROOT` is the deploy path set as a GitHub Actions secret.

```dotenv
MAIL_MAILER=smtp
MAIL_SCHEME=smtp
MAIL_HOST=<provider SMTP host>
MAIL_PORT=587
MAIL_USERNAME=<provider username>
MAIL_PASSWORD=<provider password>
MAIL_FROM_ADDRESS="<address on the verified domain>"
MAIL_FROM_NAME="<sender name>"
MAIL_OWNER_ADDRESS=<inbox that receives submissions>
```

Use `MAIL_SCHEME=smtps` with `MAIL_PORT=465` if the provider requires implicit TLS. Quote any value that contains spaces. Never commit these values.

## 3. Apply the change

Config is cached and Horizon keeps it in memory, so editing `.env` has no effect until you run:

```bash
cd "$APP_ROOT/current"
php artisan config:cache
sudo -n /usr/bin/supervisorctl restart horizon
sudo -n systemctl reload php8.4-fpm
```

The next deploy also rebuilds the cache and restarts Horizon, but don't rely on that for the test below.

## 4. Test it

Send a direct test mail. This checks the provider settings without using the queue:

```bash
php artisan tinker --execute="Mail::raw('Mail test', fn (\$m) => \$m->to(config('mail.owner_address'))->subject('Mail test'));"
```

Then submit the public contact form and confirm that:

- one email arrives at `MAIL_OWNER_ADDRESS`
- replying to it addresses the requester
- the message appears in admin Messages

## Troubleshooting

| Symptom                                  | Check                                                                                                                                                                  |
| ---------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Nothing arrives and no job appears       | `MAIL_OWNER_ADDRESS` is empty, or config wasn't re-cached. Run `php artisan config:show mail.owner_address`.                                                           |
| Mail appears in `storage/logs` instead   | `MAIL_MAILER` is still `log`, or config wasn't re-cached.                                                                                                              |
| Direct test works but form mail does not | Horizon isn't running or has stale config. Run `php artisan horizon:status`, then restart it.                                                                          |
| Job failed                               | Run `php artisan queue:failed` to see the error. After fixing it, run `php artisan queue:retry all`. Failed sends can be retried because the message is already saved. |
| Mail lands in spam                       | Check SPF and DKIM, and that `MAIL_FROM_ADDRESS` is on the verified domain. Don't set it to the requester or to a free mailbox address.                                |

## Disable mail

Set `MAIL_OWNER_ADDRESS=` to empty and repeat step 3. Nothing is sent, and submissions still reach admin Messages.
