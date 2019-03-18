var index = httpVueLoader("../../Views/BackOffice/Pages/index.vue");

const routes = [
    { 
        path: "/", 
        component: index
    }
]

const router = new VueRouter({ routes });
new Vue({ router }).$mount('#app');