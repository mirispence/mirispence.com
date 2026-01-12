import DashboardController from './DashboardController'
import ArtworkController from './ArtworkController'
import GalleryController from './GalleryController'
import BookController from './BookController'
import ChapterController from './ChapterController'
import TagController from './TagController'
import FeaturedItemController from './FeaturedItemController'
import MessageController from './MessageController'
const Admin = {
    DashboardController: Object.assign(DashboardController, DashboardController),
ArtworkController: Object.assign(ArtworkController, ArtworkController),
GalleryController: Object.assign(GalleryController, GalleryController),
BookController: Object.assign(BookController, BookController),
ChapterController: Object.assign(ChapterController, ChapterController),
TagController: Object.assign(TagController, TagController),
FeaturedItemController: Object.assign(FeaturedItemController, FeaturedItemController),
MessageController: Object.assign(MessageController, MessageController),
}

export default Admin