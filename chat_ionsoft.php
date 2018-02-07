<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <!--[if IE]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
    <title>CHAT</title>
    <!-- BOOTSTRAP CORE STYLE CSS -->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://www.gstatic.com/firebasejs/4.9.0/firebase.js"></script>
    <script src="https://www.gstatic.com/firebasejs/4.6.2/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/4.6.2/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/4.6.2/firebase-database.js"></script>
    <script src="https://www.gstatic.com/firebasejs/4.6.2/firebase-messaging.js"></script>

    <script>
        // Initialize Firebase
        var config = {
            apiKey: "AIzaSyBNQpJdt3yuFEp3ie2R6of_lP0ouI-irII",
            authDomain: "fir-data-8a936.firebaseapp.com",
            databaseURL: "https://fir-data-8a936.firebaseio.com",
            projectId: "fir-data-8a936",
            storageBucket: "",
            messagingSenderId: "544806852434"
        };
        firebase.initializeApp(config);

        // Get a reference to the database service
        var database = firebase.database();

//        function writeUserData(userId, name, email, imageUrl) {
//            firebase.database().ref('users/' + userId).set({
//                username: name,
//                email: email,
//                profile_picture : imageUrl
//            });
//        }
//        function writeData(name, email, imageUrl) {
//            var postData = {
//                username: name,
//                email: email,
//                profile_picture : imageUrl
//            };
//            // Get a key for a new Post.
//            var newPostKey = firebase.database().ref('users/').push().key;
//            // Write the new post's data simultaneously in the posts list and the user's post list.
//            var updates = {};
//            updates['/users/' + newPostKey] = postData;
//            firebase.database().ref().update(updates);
//        }
        function send() {
            var userInput = $("#input").val();
            var username = $("#username").val();
            var postData = {
                username: username,
                avatar : "assets/img/woman.png",
                message : userInput,
                date : new Date().getTime()
            };
            // Dapatkan kunci untuk Posting baru..
            var newPostKey = firebase.database().ref('prof/').push().key;
            // Tulis data posting baru secara bersamaan di daftar posting dan daftar posting pengguna.
            var updates = {};
            updates['/prof/' + newPostKey] = postData;
            firebase.database().ref().update(updates);
			$("#input").val("");
        }


        /*var commentsRef = firebase.database().ref('prof/');
        commentsRef.on('child_added', function(data) {
            $("#boxchat").append("<div>"+data.val().username+"</div>");
        });

        commentsRef.on('child_changed', function(data) {

        });

        commentsRef.on('child_removed', function(data) {

        });*/
		
		//firebase.database().ref('prof/').on('value', function(snapshot) {
		firebase.database().ref('prof/').on('child_added', function(childSnapshot) {
		/*snapshot.forEach(function(childSnapshot) {*/
			var childKey = childSnapshot.key;
			var childAvatar = childSnapshot.val().avatar;
			var childMessage = childSnapshot.val().message;
			var childUsername = childSnapshot.val().username;
			var childDate = childSnapshot.val().date;
			var date = new Date(childDate);
			var postElement ='<li class="media" id="box_'+childKey+'">';
            postElement = postElement + '<div class="media-body">';
			postElement = postElement + '<div class="media">';
            postElement = postElement + '<a class="pull-left" href="#">';
            postElement = postElement + '<img class="media-object img-circle " src="'+childAvatar+'" />';
            postElement = postElement + '</a>';
            postElement = postElement + '<div class="media-body" >';
            postElement = postElement + '<div id="msg_'+childKey+'">'+childMessage+'</div>';
            postElement = postElement + '<br />';
            postElement = postElement + '<small class="text-muted" id="usr_'+childKey+'" >'+childUsername+' | '+date.toString()+'</small>';
            postElement = postElement + ' | <span style="cursor:pointer;" onclick="deleteData('+"'"+childKey+"'"+')">Delete </span> ';
            postElement = postElement + '<hr/> ';
            postElement = postElement + '</div>';
            postElement = postElement + '</div>';
            postElement = postElement + '</div>';
            postElement = postElement + '</li>';
            $("#boxchat").append(postElement);
		  /*});*/
		});
		function deleteData(key){
			firebase.database().ref('/prof/' + key).remove();
		}

		firebase.database().ref('prof/').on('child_changed', function(childSnapshot) {
			var childKey = childSnapshot.key;
			var childMessage = childSnapshot.val().message;
			var childUsername = childSnapshot.val().username;
			var childDate = childSnapshot.val().date;
			var date = new Date(childDate);
			$("#msg_"+childKey).html(childMessage);
			$("#usr_"+childKey).html(childUsername+' | '+date.toString());
		});

		firebase.database().ref('prof/').on('child_removed', function(childSnapshot) {
			var childKey = childSnapshot.key;
			$("#box_"+childKey).remove();
		});
    </script>
</head>
<body style="font-family:Verdana">
<?php date_default_timezone_set('asia/jakarta');?>
<div class="container">
    <div class="row " style="padding-top:40px;">
        <h3 class="text-center" > CHAT IONSoft </h3>
        <br /><br />
        <div class="col-md-8">
            <div class="panel panel-info">
                <div class="panel-heading">
                    RECENT CHAT HISTORY
                </div>
                <div class="panel-body">
                    <ul class="media-list" id="boxchat">

                    </ul>
                </div>
                <div class="panel-footer">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Enter Message" id="input"/>
                        <input type="hidden" class="form-control" value="<?php echo getenv("username"); ?>" id="username"/>
                        <span class="input-group-btn">
                            <button class="btn btn-info" type="button" onclick="send()">SEND</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>
<!--        <script type="text/javascript">-->
<!--            function changeText2(){-->
<!--                var userInput =  document.getElementById('userInput').value;-->
<!--                document.getElementById('boldStuff2').innerHTML = userInput;-->
<!--            }-->
<!--        </script>-->
<!--        <p>Welcome to the site <b id='boldStuff2'>dude</b> </p>-->
<!--        <input type='text' id='userInput' value='Enter Text Here' />-->
<!--        <input type='button' onclick='changeText2()' value='Change Text'/>-->
        <!--<div class="col-md-4">-->
            <!--<div class="panel panel-primary">-->
                <!--<div class="panel-heading">-->
                    <!--ONLINE USERS-->
                <!--</div>-->
                <!--<div class="panel-body">-->
                    <!--<ul class="media-list">-->

                        <!--<li class="media">-->

                            <!--<div class="media-body">-->

                                <!--<div class="media">-->
                                    <!--<a class="pull-left" href="#">-->
                                        <!--<img class="media-object img-circle" style="max-height:40px;" src="assets/img/user.png" />-->
                                    <!--</a>-->
                                    <!--<div class="media-body" >-->
                                        <!--<h5>Alex Deo | User </h5>-->

                                        <!--<small class="text-muted">Active From 3 hours</small>-->
                                    <!--</div>-->
                                <!--</div>-->

                            <!--</div>-->
                        <!--</li>-->
                        <!--<li class="media">-->

                            <!--<div class="media-body">-->

                                <!--<div class="media">-->
                                    <!--<a class="pull-left" href="#">-->
                                        <!--<img class="media-object img-circle" style="max-height:40px;" src="assets/img/user.gif" />-->
                                    <!--</a>-->
                                    <!--<div class="media-body" >-->
                                        <!--<h5>Jhon Rexa | User </h5>-->

                                        <!--<small class="text-muted">Active From 3 hours</small>-->
                                    <!--</div>-->
                                <!--</div>-->

                            <!--</div>-->
                        <!--</li>-->
                        <!--<li class="media">-->

                            <!--<div class="media-body">-->

                                <!--<div class="media">-->
                                    <!--<a class="pull-left" href="#">-->
                                        <!--<img class="media-object img-circle" style="max-height:40px;" src="assets/img/user.png" />-->
                                    <!--</a>-->
                                    <!--<div class="media-body" >-->
                                        <!--<h5>Alex Deo | User </h5>-->

                                        <!--<small class="text-muted">Active From 3 hours</small>-->
                                    <!--</div>-->
                                <!--</div>-->

                            <!--</div>-->
                        <!--</li>-->
                        <!--<li class="media">-->

                            <!--<div class="media-body">-->

                                <!--<div class="media">-->
                                    <!--<a class="pull-left" href="#">-->
                                        <!--<img class="media-object img-circle" style="max-height:40px;" src="assets/img/user.gif" />-->
                                    <!--</a>-->
                                    <!--<div class="media-body" >-->
                                        <!--<h5>Jhon Rexa | User </h5>-->

                                        <!--<small class="text-muted">Active From 3 hours</small>-->
                                    <!--</div>-->
                                <!--</div>-->

                            <!--</div>-->
                        <!--</li>-->
                        <!--<li class="media">-->

                            <!--<div class="media-body">-->

                                <!--<div class="media">-->
                                    <!--<a class="pull-left" href="#">-->
                                        <!--<img class="media-object img-circle" style="max-height:40px;" src="assets/img/user.png" />-->
                                    <!--</a>-->
                                    <!--<div class="media-body" >-->
                                        <!--<h5>Alex Deo | User </h5>-->

                                        <!--<small class="text-muted">Active From 3 hours</small>-->
                                    <!--</div>-->
                                <!--</div>-->

                            <!--</div>-->
                        <!--</li>-->
                        <!--<li class="media">-->

                            <!--<div class="media-body">-->

                                <!--<div class="media">-->
                                    <!--<a class="pull-left" href="#">-->
                                        <!--<img class="media-object img-circle" style="max-height:40px;" src="assets/img/user.gif" />-->
                                    <!--</a>-->
                                    <!--<div class="media-body" >-->
                                        <!--<h5>Jhon Rexa | User </h5>-->

                                        <!--<small class="text-muted">Active From 3 hours</small>-->
                                    <!--</div>-->
                                <!--</div>-->

                            <!--</div>-->
                        <!--</li>-->
                        <!--<li class="media">-->

                            <!--<div class="media-body">-->

                                <!--<div class="media">-->
                                    <!--<a class="pull-left" href="#">-->
                                        <!--<img class="media-object img-circle" style="max-height:40px;" src="assets/img/user.png" />-->
                                    <!--</a>-->
                                    <!--<div class="media-body" >-->
                                        <!--<h5>Alex Deo | User </h5>-->

                                        <!--<small class="text-muted">Active From 3 hours</small>-->
                                    <!--</div>-->
                                <!--</div>-->

                            <!--</div>-->
                        <!--</li>-->
                        <!--<li class="media">-->

                            <!--<div class="media-body">-->

                                <!--<div class="media">-->
                                    <!--<a class="pull-left" href="#">-->
                                        <!--<img class="media-object img-circle" style="max-height:40px;" src="assets/img/user.gif" />-->
                                    <!--</a>-->
                                    <!--<div class="media-body" >-->
                                        <!--<h5>Jhon Rexa | User </h5>-->

                                        <!--<small class="text-muted">Active From 3 hours</small>-->
                                    <!--</div>-->
                                <!--</div>-->

                            <!--</div>-->
                        <!--</li>-->
                    <!--</ul>-->
                <!--</div>-->
            <!--</div>-->

        <!--</div>-->
    </div>
</div>
</body>
</html>
