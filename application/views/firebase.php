<html>
<head>
 <title>Firebase Realtime Database Web</title>

 <?php
 	$main = $broadcastId;
	$userId = $userId;
	$useranme = $useranme;
	$userImage = $userImage;
  $comment = $comment;
?>
 <script src="https://www.gstatic.com/firebasejs/4.9.0/firebase.js"></script>
 <script>
	 var config ={
		apiKey: "AIzaSyDVnP0BFc359plC86lw45FyBfAM6QVyojI",
		authDomain: "instahit-2c7e6.firebaseapp.com",
		databaseURL: "https://instahit-2c7e6.firebaseio.com",
		projectId: "instahit-2c7e6",
		storageBucket: "instahit-2c7e6.appspot.com",
		messagingSenderId: "721224595170",
		appId: "1:721224595170:web:bdfb5a766d325b9cf9d0bb",
		measurementId: "G-DHW3YFF87X"
	};

   firebase.initializeApp(config);
 </script>
 <script>

  function save_user(){
   var user_name = '<?php echo $useranme; ?>';
   var user_id = '<?php echo $userId; ?>';
	 var user_image = '<?php echo $userImage; ?>';
	 var comment = '<?php echo $comment; ?>';
   var data = {
    user_id: user_id,
		user_name: user_name,
		user_image: user_image,
    comment: comment
   }
   var updates = {};
   updates['/<?php echo $main;?>/' + user_id] = data;
   firebase.database().ref().update(updates);

   alert('The user is created successfully!');
   reload_page();
  }


 </script>

</body>
</html>
