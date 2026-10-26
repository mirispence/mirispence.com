import HomeController from './HomeController'
import GalleryController from './GalleryController'
import ArtworkController from './ArtworkController'
import BookController from './BookController'
import ContactController from './ContactController'
import MatureConsentController from './MatureConsentController'
const Public = {
    HomeController: Object.assign(HomeController, HomeController),
GalleryController: Object.assign(GalleryController, GalleryController),
ArtworkController: Object.assign(ArtworkController, ArtworkController),
BookController: Object.assign(BookController, BookController),
ContactController: Object.assign(ContactController, ContactController),
MatureConsentController: Object.assign(MatureConsentController, MatureConsentController),
}

export default Public