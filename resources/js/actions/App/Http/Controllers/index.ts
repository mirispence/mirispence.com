import Public from './Public'
import MediaConversionController from './MediaConversionController'
import Settings from './Settings'
import Admin from './Admin'
const Controllers = {
    Public: Object.assign(Public, Public),
MediaConversionController: Object.assign(MediaConversionController, MediaConversionController),
Settings: Object.assign(Settings, Settings),
Admin: Object.assign(Admin, Admin),
}

export default Controllers