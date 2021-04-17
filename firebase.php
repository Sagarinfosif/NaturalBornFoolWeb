<html>
<head>
 <title>Firebase Realtime Database Web</title>

 <?php
 	$main = $_GET['broadcastId'];
	$userId = $_GET['userId'];
	$useranme = $_GET['username'];
	$userImage = $_GET['userImage'];
  $comment = $_GET['comment'];
?>
 <script src="https://www.gstatic.com/firebasejs/4.9.0/firebase.js"></script>
 <script>
	 var config ={
		apiKey: "AIzaSyCV9zw0-bj4u4W23fYw-0jJOXvPnA8LPDY",
    authDomain: "talvide-new.firebaseapp.com",
    databaseURL: "https://talvide-new.firebaseio.com",
    projectId: "talvide-new",
    storageBucket: "talvide-new.appspot.com",
    messagingSenderId: "77802199472",
    appId: "1:77802199472:web:6496435ec9218144739e75",
    measurementId: "G-LLXLEFZSFH"
	};

   firebase.initializeApp(config);
 </script>
 <script>
  window.onload = function(){
   var user_name = '<?php echo $useranme; ?>';
   var user_id = '<?php echo $userId; ?>';
	 var user_image = '<?php echo $userImage; ?>';
	 var comment = '<?php echo $comment; ?>';
	 var uid = firebase.database().ref().child('users').push().key;
   var data = {
    user_id: user_id,
		user_name: user_name,
		user_image: user_image,
    comment: comment
   }
   var updates = {};
   updates['/<?php echo $main;?>/' + uid] = data;
   firebase.database().ref().update(updates);
  }


 </script>

</body>
</html>
