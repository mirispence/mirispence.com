import HomeController from './HomeController'
import ArtworkController from './ArtworkController'
import GalleryController from './GalleryController'
import BookController from './BookController'
import ContactController from './ContactController'
import MatureConsentController from './MatureConsentController'
const Public = {
    HomeController: Object.assign(HomeController, HomeController),
ArtworkController: Object.assign(ArtworkController, ArtworkController),
GalleryController: Object.assign(GalleryController, GalleryController),
BookController: Object.assign(BookController, BookController),
ContactController: Object.assign(ContactController, ContactController),
MatureConsentController: Object.assign(MatureConsentController, MatureConsentController),
}

export default Public