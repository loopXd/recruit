import settings from './modules/settings/Settings';
import user from './modules/user/User';
import theme from './modules/theme/Theme';
import userAndRoles from './modules/user/UserRoles';
import notificationSettings from './modules/settings/NotificationSettings';
import statuses from "./modules/statuses/Statuses";

// App
import loader from "./modules/app/Loader";
import jobs from "./modules/app/Jobs";

export default{
    modules: {
        theme,
        settings,
        user,
        userAndRoles,
        notificationSettings,
        statuses,
        // App
        loader,
        jobs,
    }
}
