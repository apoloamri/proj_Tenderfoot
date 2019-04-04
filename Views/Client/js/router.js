var index = httpVueLoader("/Views/Client/pages/index.vue");
var login = httpVueLoader("/Views/Client/pages/login.vue");
var register = httpVueLoader("/Views/Client/pages/register.vue");

const routes = [
    { 
        path: "/", 
        component: index
    },
    { 
        path: "/login", 
        component: login
    },
    { 
        path: "/register", 
        component: register
    }
]

const router = new VueRouter({ routes });
new Vue({ router }).$mount('#app');