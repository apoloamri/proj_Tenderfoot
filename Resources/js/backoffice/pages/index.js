// new Vue({
//     el: "#adminInnerContent",
//     data: {
//         Logs: []
//     },
//     methods: {
//         GetLogs() {
//             var self = this;
//             Loading(true);
//             axios
//             .get("/api/logs")
//             .then(function (response) {
//                 self.Logs = response.data.Result;
//                 Loading(false);
//             })
//             .catch(function (error) {
//                 console.log(error);
//             });
//         }
//     },
//     mounted() {
//         window.addEventListener("load", () => {
//             this.GetLogs();
//         });
//     }
// });