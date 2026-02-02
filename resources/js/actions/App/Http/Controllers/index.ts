import Public from './Public'
import Settings from './Settings'
import Admin from './Admin'
const Controllers = {
    Public: Object.assign(Public, Public),
Settings: Object.assign(Settings, Settings),
Admin: Object.assign(Admin, Admin),
}

export default Controllers