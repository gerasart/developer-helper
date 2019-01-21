import Vue from 'vue'
import App from './App'
import router from './pages/router'
// import menuFix from './utils/admin-menu-fix'
import adminPageFix from './utils/admin-page-fix'


let app = document.querySelector('#app');
if ( app ) {
    adminPageFix();

    /* eslint-disable no-new */
    new Vue({
        el: '#app',
        // data: {
        //     currentRoute: window.location.pathname
        // },
        router,
        render: h => h(App),
        created() {
            // this.$store.commit(types.RESET_LOADING_PROGRESS)
            // this.$store.dispatch('getAllCategories')
            // this.$store.dispatch('getAllPages')

            // Once user is signed in/out, uncomment if you need Firebase authentication
            // auth.onAuthStateChanged(user => {
            //   if (user) {
            //     this.$store.commit(types.LOGIN_USER)
            //     this.$store.commit(types.STORE_FETCHED_USER, user)
            //   } else {
            //     this.$store.commit(types.LOGOUT_USER)
            //   }
            // })
        }
    });
}
// menuFix('demo-creator');