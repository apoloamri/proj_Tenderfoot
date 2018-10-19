<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tenderfoot</title>
    <script src="https://cdn.jsdelivr.net/npm/vue@2.5.13/dist/vue.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
    <link rel="stylesheet" type="text/css" href="/Resources/css/style.css">
</head>
<body>
    <div id="container">
        <?php $model->RenderPage(); ?>
    </div>
    <!-- <footer>
        <div>Icons made by <a href="https://www.flaticon.com/authors/gregor-cresnar" title="Gregor Cresnar">Gregor Cresnar</a> from <a href="https://www.flaticon.com/" title="Flaticon">www.flaticon.com</a> is licensed by <a href="http://creativecommons.org/licenses/by/3.0/" title="Creative Commons BY 3.0" target="_blank">CC 3.0 BY</a></div>
    </footer> -->
    <script type="module">
        import Lib from "/Resources/js/lib.js";
        new Vue({
            methods: {
                GetSession: function () {
                    var self = this;
                    Lib.Get("/api/session", {
                        "session": Lib.GetCookie("session_id")
                    },
                    function (success) {
                        Lib.SetCookie("session_id", success.session, 1);
                    });
                }
            },
            created() {
                this.GetSession();
            }
        });
    </script>
</body>
</html>