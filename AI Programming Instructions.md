# Agent Task Specification: Art and Writing Portfolio Site

## 0. Context and Overall Objective

You are building a personal website and lightweight CMS for a single creator. The primary purpose is to host an art portfolio and a books/writing section, with a minimal but solid admin interface.

The site must be:

* Small, maintainable, and easy for one experienced PHP developer to extend.
* Built with modern PHP 8.3 practices and a mainstream framework.
* Image focused for art, with a separate section for books/writing.
* Ready for future Stripe integration for art commissions, without doing Stripe now.

The user is comfortable with Laravel and PHP and will later maintain and extend this codebase.

Your job: design and implement the initial version of the site according to the specs below.

---

## 1. High Level Architecture

* Monolithic server rendered web app.
* Backend: PHP 8.3 application using Laravel style MVC, with services for domain logic.
* Database: MySQL or MariaDB.
* Frontend: Blade templates, Tailwind CSS, and small sprinkles of Alpine.js.
* Storage: Laravel filesystem (Flysystem) for uploaded media with a storage symlink for public assets.
* Email and queues: Laravel mailers and (optional) queues for background tasks such as image conversion and notifications.

Separation of concerns:

* Public side: portfolio browsing, artwork detail, books and chapters, contact form.
* Admin side: authenticated CMS for managing artworks, galleries, books, chapters, tags, featured items, and contact/commission messages.

---

## 2. Tech Stack and Conventions

Framework and environment:

* PHP: 8.3
* Framework: Laravel 12 (or the latest stable 12.x line).
* Web server: Nginx or Apache with PHP FPM, standard Laravel deployment.
* Database: MySQL 8.x or MariaDB 10.x.
* ORM: Eloquent.

Frontend:

* Templating: Blade.
* CSS: Tailwind CSS, built via Vite.
* JS: Vanilla JavaScript plus Alpine.js for lightweight interactivity.
* No SPA frontend and no heavy frontend frameworks.

Libraries and packages:

* Image handling: `spatie/laravel-medialibrary` for storing media and generating conversions.
* Markdown for chapters: `league/commonmark` with a safe configuration.
* Optional HTML sanitization for user content: `mewebstudio/purifier` or similar, used where necessary.
* Authentication scaffolding: Laravel Breeze or Jetstream, configured for email plus password admin login.
* Testing: Pest with Laravel plugin.

Code style:

* Use PSR 12 standards and idiomatic Laravel patterns.
* Use strict typing where possible in PHP code.
* Group logic into controllers, form requests, models, and service classes.

---

## 3. Domain Model and Data Structures

Define and implement the following domain entities.

### 3.1 Users

Represents admin users.

Fields:

* `id` (big integer, primary key)
* `name` (string, 120)
* `email` (string, unique)
* `password` (hashed using Laravel defaults, Argon2id)
* `role` (enum or string, e.g. `admin` for now)
* `remember_token`
* Timestamps

Only admin users are needed in the initial version.

### 3.2 Artworks

Represents a single piece of art.

Fields:

* `id`
* `title` (string, required)
* `slug` (string, unique, used for URLs)
* `description` (text, nullable)
* `alt_text` (string, nullable)
* `created_on` (date of the artwork itself, nullable)
* `publish_status` (`enum('draft','published')`)
* `nsfw_flag` (boolean)
* `featured_flag` (boolean, for homepage)
* `metadata` (JSON, optional additional metadata)
* Timestamps

Relationships:

* Many to many with `galleries`.
* Many to many with `tags` (artwork tags).
* Media via Spatie medialibrary:

  * Collection name: `artwork`.
  * Conversions: `thumb`, `md`, `xl`.

### 3.3 Galleries

Logical grouping for artworks.

Fields:

* `id`
* `name` (string)
* `slug` (string, unique)
* `description` (text, nullable)
* `sort_order` (integer, nullable)
* `publish_status` (`enum('draft','published')`)
* Timestamps

Relationships:

* Many to many with `artworks` (pivot `artwork_gallery` with `sort_order`).

### 3.4 Tags

Common tag entity used for artworks and books.

Fields:

* `id`
* `name` (string)
* `slug` (string, unique)
* `type` (`enum('artwork','book','both')` or nullable, to scope tags)
* Timestamps

Relationships:

* Many to many with `artworks` (`artwork_tag`).
* Many to many with `books` (`book_tag`).

### 3.5 Books

Represents a long form work such as a novel or collection.

Fields:

* `id`
* `title` (string)
* `slug` (string, unique)
* `description` (long text for blurb)
* `publish_status` (`enum('draft','published')`)
* `featured_flag` (boolean, for homepage)
* `release_date` (date, nullable)
* `external_links` (JSON, holds an array of label/URL pairs such as "Amazon", "Itch", "Download")
* Timestamps

Relationships:

* Media collection `cover` for cover image.
* Many to many with `tags` via `book_tag`.
* One to many with `chapters`.

### 3.6 Chapters

Sections of a book.

Fields:

* `id`
* `book_id` (foreign key to `books`)
* `title` (string)
* `slug` (string, unique per book)
* `summary` (text, nullable)
* `body_markdown` (long text, stored as Markdown)
* `order` (integer for sorting within the book)
* `is_sample` (boolean, indicates if this chapter is shown as a sample)
* Timestamps

### 3.7 Contact Messages

Represents a message submitted via the contact or commission form.

Fields:

* `id`
* `name` (string)
* `email` (string)
* `subject` (string)
* `message` (text)
* `type` (`enum('general','commission')`)
* `status` (`enum('new','in_review','handled','spam')`)
* `metadata` (JSON: IP, user agent, rate limiting info)
* Timestamps

Relationships:

* One to one with `commission_requests` for commission related messages.

### 3.8 Commission Requests

Future facing structure for handling commissions and linking to Stripe later.

Fields:

* `id`
* `contact_message_id` (foreign key)
* `project_description` (long text, optional expanded detail)
* `budget_range` (string, optional, human readable like "$100-$300")
* `desired_due_date` (date, nullable)
* `status` (`enum('new','quoted','accepted','rejected','completed')`)
* `quote_amount` (decimal(10,2), nullable)
* `invoice_link` (string, nullable, will store Stripe payment link later)
* Timestamps

### 3.9 Featured Items

Used to control what appears as featured on the homepage or other contexts.

Fields:

* `id`
* `item_type` (`enum('artwork','book')`)
* `item_id` (integer)
* `display_context` (string, e.g. `home`, `gallery`)
* `display_order` (integer)
* Timestamps

---

## 4. Database and Migrations

Implement migrations in Laravel for all entities above, including:

* Primary keys.
* Foreign keys.
* Unique indexes on slugs and emails.
* Indexes on `publish_status`, `featured_flag`, and `type` fields where appropriate.

Use standard Laravel migration style with `Schema::create` and correct column types. Ensure all pivot tables have foreign keys and appropriate indexes.

---

## 5. Routing and URL Structure

Public routes:

* `/`

  * Controller: `Public\HomeController@index`
  * Shows featured artworks and featured books.
* `/art`

  * Controller: `Public\ArtworkController@index`
  * Shows paginated artworks. Supports filters via query parameters:

    * `?tag=tag-slug`
    * `?gallery=gallery-slug`
    * `?nsfw=0/1` (optional).
* `/art/galleries`

  * Controller: `Public\GalleryController@index`
  * Shows all galleries.
* `/art/galleries/{slug}`

  * Controller: `Public\GalleryController@show`
  * Shows a single gallery and its artworks.
* `/art/{slug}`

  * Controller: `Public\ArtworkController@show`
  * Shows a single artwork and its metadata.
* `/books`

  * Controller: `Public\BookController@index`
  * Shows all published books with their covers.
* `/books/{slug}`

  * Controller: `Public\BookController@show`
  * Shows a specific book, its description, and list of chapters. Includes sample chapters and external links.
* `/contact`

  * Controller: `Public\ContactController@create` (GET) and `store` (POST).
  * Accepts optional `type=commission` query parameter to preselect commission mode.

Admin routes (behind auth, `/admin` prefix):

* `/admin` - dashboard (basic stats and quick links).
* Resource controllers for:

  * `/admin/artworks`
  * `/admin/galleries`
  * `/admin/books`
  * `/admin/chapters`
  * `/admin/tags`
  * `/admin/featured`
  * `/admin/messages`
  * `/admin/commissions`

Use Laravel resource controllers where appropriate. All admin routes must be protected with an `auth` middleware and optionally an `is_admin` middleware or policy.

---

## 6. Backend Structure and File Organization

Use the standard Laravel layout with additional logical grouping:

* `app/Http/Controllers/Public` for public controllers.
* `app/Http/Controllers/Admin` for admin controllers.
* `app/Http/Requests` for form requests (validation).
* `app/Models` for Eloquent models.
* `app/Services` for domain services:

  * `app/Services/Images` for image and media handling.
  * `app/Services/Messaging` for contact notifications.
  * `app/Services/Commission` for future Stripe integration.
* `app/Policies` for authorization policies if needed.

Views:

* `resources/views/layouts` - base layout templates.
* `resources/views/public` - public facing pages:

  * `/art`, `/galleries`, `/books`, `/contact`, etc.
* `resources/views/admin` - admin CMS views.

Assets:

* `resources/css` - Tailwind CSS entry file.
* `resources/js` - Alpine bootstrap and any small controllers.

Storage:

* Use Laravel `storage/app` for originals.
* Use Spatie medialibrary conversions for web ready sizes in `storage/app/public`.
* Use `php artisan storage:link` so `public/storage` exposes conversions.

---

## 7. Content Management Flows

### 7.1 Authentication and Admin Access

* Use Laravel Breeze or Jetstream to scaffold login and basic auth.
* Restrict admin routes with middleware:

  * `auth`
  * A custom `can:admin` or role based check on `user->role === 'admin'`.

### 7.2 Artwork Management

Admin can:

* Create artworks:

  * Enter title, slug (auto generated from title), description, alt text, created date, NSFW flag, publish status, featured flag.
  * Select one or more galleries.
  * Add tags via a multi select, with the ability to create new tags.
  * Upload one or more images for the artwork to the `artwork` media collection.
* On image upload:

  * Store original on a private or protected disk.
  * Generate conversions:

    * `thumb` (small)
    * `md` (medium)
    * `xl` (large display)
* Edit and delete artworks.
* Toggle `publish_status` and `featured_flag`.

### 7.3 Gallery Management

Admin can:

* Create, edit, delete galleries.
* Set name, slug, description, publish status, sort order.
* Choose a primary cover image from the artwork media or a separate media collection if needed.

### 7.4 Tag Management

Admin can:

* Create tags with name and type (artwork, book, both).
* Edit and delete tags.
* Assign tags within artwork and book forms.

### 7.5 Book and Chapter Management

Books:

* Create, edit, delete books.
* Set title, slug, description, publish status, featured flag, release date.
* Assign tags.
* Attach a cover image via media collection `cover`.
* Add external links such as store or download links stored as JSON.

Chapters:

* For each book, create multiple chapters.
* Use a Markdown field for body content.
* Provide an `is_sample` flag to control which chapters are shown publicly as samples.
* Order chapters by `order`.

### 7.6 Contact and Commission Inbox

Contact form:

* Validates input server side using a `ContactRequest` form request.
* For commission type, includes extra fields such as `budget_range` and `desired_due_date`.
* Stores message in `contact_messages` and optional `commission_requests`.
* Applies rate limiting and spam protections (honeypot, time based trap).
* Sends an email notification to the configured admin email.

Admin inbox:

* List all messages with filters:

  * Status, type, search by subject or email.
* View details of a message.
* Change status (new, in review, handled, spam).
* If a message is a commission:

  * Create or update `commission_requests`.
  * Set status and quote amount.
  * Store `invoice_link` when Stripe is added later.

---

## 8. Security and Best Practices

Apply at least the following:

* CSRF protection: rely on Laravel CSRF middleware and include `@csrf` in all forms.
* Input validation: use FormRequest classes for all POST and PUT operations.
* Output escaping: Blade escapes by default. Only allow raw HTML where necessary and sanitized.
* SQL injection: rely on Eloquent parameter binding.
* Password storage: use Laravel defaults (Argon2id).
* File upload security:

  * Validate mime type and max file size.
  * Accept only image types for artwork media.
  * Store originals outside the web root.
  * Only serve media through known conversions or URLs under `public/storage`.
* Rate limiting:

  * Use `RateLimiter` or `ThrottleRequests` middleware for `/contact` POST.
  * A reasonable default is 5 submissions per IP per hour.
* Spam protection:

  * Hidden honeypot field that must stay empty.
  * Simple timestamp check to ensure the form was not submitted instantly by a bot.
* HTTPS:

  * Assume deployment under HTTPS.
  * Configure app URL as `https://...` and enforce secure cookies.

---

## 9. Future Stripe Integration

Design data and code now so Stripe can be plugged in later.

Data:

* Use `commission_requests` as the anchor entity.
* Plan a future `payments` table with at least:

  * `id`
  * `commission_request_id`
  * `stripe_payment_intent_id`
  * `amount`
  * `currency`
  * `status`
  * `raw_payload` JSON
  * Timestamps

Code location for future Stripe support:

* `app/Services/Commission/StripeCommissionService.php`

  * Methods for creating payment links or payment intents.
  * Methods for handling webhook callbacks.
* Webhooks:

  * Route group under `/stripe/webhooks`.
  * Controller for handling events and updating the `payments` and `commission_requests`.

For this iteration, only define the `commission_requests` entity and leave Stripe code as planned but not implemented.

---

## 10. Implementation Roadmap and Work Phases

Implement in clear phases that a single developer can follow.

Phase 1 - Project setup:

* Create Laravel project.
* Configure `.env` for database, mail, app URL.
* Install Breeze (or Jetstream) for auth.
* Install Tailwind CSS and configure Vite build.
* Install required packages:

  * Spatie medialibrary
  * CommonMark for Markdown
* Run initial migrations and confirm app boots.

Phase 2 - Core models and migrations:

* Implement migrations and Eloquent models for:

  * Users (if using Laravel default, adjust as needed)
  * Artworks, galleries, pivot tables
  * Tags, pivot tables
  * Books, chapters
  * Contact messages, commission requests
  * Featured items
* Add model relationships and casts.

Phase 3 - Public site:

* Create layout and base CSS.
* Implement public controllers and views:

  * Home page (featured artworks and books).
  * Art index, gallery index, gallery detail, artwork detail.
  * Books index and book detail with sample chapters.
* Add filtering by tag and gallery in the art index.
* Implement image display using medialibrary conversions and basic responsive image attributes.
* Add basic SEO tags: title, description, OpenGraph image where appropriate.

Phase 4 - Admin CMS:

* Create admin layout and navigation.
* Protect `/admin` routes with auth and admin check.
* Implement CRUD for:

  * Artworks and galleries with media upload.
  * Tags.
  * Books and chapters.
  * Featured items.
* Use form requests for validation and integrate medialibrary for file uploads.

Phase 5 - Contact and commissions:

* Implement public contact form with both general and commission modes.
* Add validation, spam protection, and rate limiting.
* Store messages and commission requests to the database.
* Implement mail notification to admin.
* Build admin inbox for messages and commissions with status changes.

Phase 6 - Polish and testing:

* Write basic feature tests for:

  * Viewing public pages.
  * Creating artworks in admin.
  * Submitting contact form.
* Add accessibility and basic ARIA labels where needed.
* Document how to add new galleries, artworks, books, and chapters.

Phase 7 - Stripe integration preparation:

* Add extra fields to `commission_requests` if needed.
* Create placeholder `StripeCommissionService` with empty stubs and comments describing future behavior.

---

## 11. Starter Code Examples

Use the following as the starting style for implementation.

### 11.1 Example route and controller

```php
// routes/web.php

use App\Http\Controllers\Public\ArtworkController;

Route::get('/art/{artwork:slug}', [ArtworkController::class, 'show'])
    ->name('artwork.show');
```

```php
// app/Http/Controllers/Public/ArtworkController.php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Artwork;

class ArtworkController extends Controller
{
    public function index()
    {
        // handle filtering by tag and gallery via query parameters
    }

    public function show(Artwork $artwork)
    {
        $artwork->load(['galleries', 'tags', 'media']);

        return view('public.artworks.show', [
            'artwork' => $artwork,
        ]);
    }
}
```

### 11.2 Example Artwork model

```php
// app/Models/Artwork.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Artwork extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'alt_text',
        'created_on',
        'publish_status',
        'nsfw_flag',
        'featured_flag',
        'metadata',
    ];

    protected $casts = [
        'created_on' => 'date',
        'nsfw_flag' => 'boolean',
        'featured_flag' => 'boolean',
        'metadata' => 'array',
    ];

    public function galleries()
    {
        return $this->belongsToMany(Gallery::class)
            ->withPivot('sort_order')
            ->withTimestamps();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
}
```

### 11.3 Example artwork detail view

```blade
{{-- resources/views/public/artworks/show.blade.php --}}
@extends('layouts.public')

@section('title', $artwork->title)

@section('content')
<article class="max-w-5xl mx-auto py-12">
    <header class="mb-8">
        <h1 class="text-4xl font-semibold">{{ $artwork->title }}</h1>
        @if ($artwork->created_on)
            <p class="text-gray-500 mt-2">
                {{ $artwork->created_on->format('F j, Y') }}
            </p>
        @endif

        @if ($artwork->tags->isNotEmpty())
            <div class="flex flex-wrap gap-2 mt-4">
                @foreach ($artwork->tags as $tag)
                    <a href="{{ route('art.index', ['tag' => $tag->slug]) }}"
                       class="px-3 py-1 text-sm bg-slate-200 rounded-full">
                        {{ $tag->name }}
                    </a>
                @endforeach
            </div>
        @endif
    </header>

    <figure class="mb-10">
        <img
            src="{{ $artwork->getFirstMediaUrl('artwork', 'xl') }}"
            alt="{{ $artwork->alt_text }}"
            loading="lazy"
            class="w-full rounded shadow-lg"
        >
        @if ($artwork->description)
            <figcaption class="mt-4 text-gray-700 leading-relaxed">
                {!! nl2br(e($artwork->description)) !!}
            </figcaption>
        @endif
    </figure>

    <section class="mt-12 bg-slate-100 p-6 rounded">
        <h2 class="text-2xl font-medium mb-3">Interested in a commission?</h2>
        <p class="mb-4">
            Send your project details and you will get a response with availability and a quote.
        </p>
        <a href="{{ route('contact.create', ['type' => 'commission']) }}"
           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-500">
            Contact for commission
        </a>
    </section>
</article>
@endsection
```

### 11.4 Example contact form request and controller

```php
// app/Http/Requests/ContactRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email:rfc,dns', 'max:255'],
            'subject' => ['required', 'string', 'max:150'],
            'message' => ['required', 'string', 'min:20', 'max:5000'],
            'type' => ['nullable', 'in:general,commission'],
            'budget_range' => ['nullable', 'string', 'max:100'],
            'desired_due_date' => ['nullable', 'date'],
            'honeypot' => ['prohibited'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
```

```php
// app/Http/Controllers/Public/ContactController.php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactRequest;
use App\Models\ContactMessage;
use Illuminate\Support\Facades\RateLimiter;
use App\Services\Messaging\ContactNotifier;

class ContactController extends Controller
{
    public function create()
    {
        return view('public.contact.create');
    }

    public function store(ContactRequest $request, ContactNotifier $notifier)
    {
        $key = 'contact:' . $request->ip();

        if (! RateLimiter::attempt($key, 5, function () {}, 3600)) {
            abort(429, 'Too many attempts. Please try again later.');
        }

        $data = $request->validated();
        $data['metadata'] = [
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ];

        $message = ContactMessage::create($data);

        if ($message->type === 'commission') {
            $message->commissionRequest()->create([
                'project_description' => $request->input('message'),
                'budget_range' => $request->input('budget_range'),
                'desired_due_date' => $request->input('desired_due_date'),
                'status' => 'new',
            ]);
        }

        $notifier->notifyAdmin($message);

        return redirect()
            ->route('contact.create')
            ->with('status', 'Message sent. You will receive a reply soon.');
    }
}
```

This specification and examples define what the agent should build and how it should structure the code.
