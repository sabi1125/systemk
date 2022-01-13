<?php
include 'db.php';
class PostsLogic extends db{
    public function checkEmpty($data){
        if($data["post"] === "" && $data["image_base64"] === ""){
            return false;
        }
        if($data["post"] === "" && $data["image_base64"] !== ""){
            return false;
        }
        if($data["post"] !== "" && $data["image_base64"] === ""){
            return true;
        }
    }

    public function insertPost($data){
        $image_filename = null;


        if (!empty($data['image_base64'])) {

        $base64 = preg_replace('/^data:.+base64,/', '', $_POST['image_base64']);

        $image_binary = base64_decode($base64);

        $image_filename = strval(time()) . bin2hex(random_bytes(25)) . '.png';
        
        $filepath =  '/var/www/public/image/' . $image_filename;
        file_put_contents($filepath, $image_binary);
        }
        $sql = "INSERT INTO posts(u_id,post,image) VALUES (:uid,:post,:image)";
            $stmt = $this->connect()->prepare($sql);
            $objects = [
                ":uid" => $_SESSION["id"],
                ":post" => $data["post"],
                ":image" => $image_filename
            ];
            $stmt->execute($objects);
}

    public function getFollowingPosts($lit){
        $li = $lit;
        $sql = "SELECT profiles.profilePic,users.fullname,users.username,posts.post,posts.image,posts.id FROM
        users JOIN profiles ON users.id = profiles.u_id 
        JOIN posts ON profiles.u_id = posts.u_id 
        JOIN followers ON posts.u_id = followers.following 
        WHERE followers.following != :id 
        and followers.follower = :id ORDER BY posts.id DESC LIMIT $li ;
        ";
        $stmt = $this->connect()->prepare($sql);
        $stmt->execute([
            ":id" => $_SESSION["id"],
        ]);
        $followingPosts = $stmt->fetchAll();
        return $followingPosts;
    }

    
    public function checkIfLiked($postId) {
        $sql = "SELECT * FROM likedPost WHERE post_id = :postId AND liker = :likerId";
        $stmt = $this->connect()->prepare($sql);
        $options = [
            ":postId" => $postId,
            ":likerId" => $_SESSION["id"]
        ];
        $stmt->execute($options);
        $check = $stmt->rowCount();
        if($check > 0){
            return true;
        }
        return false;
    }

    public function like($postId) {
        $check = $this->checkIfLiked($postId);

        if($check){
        $sql = "DELETE FROM likedPost WHERE post_id = :postId AND liker = :likerId";
        $stmt = $this->connect()->prepare($sql);
        $options = [
            ":postId" => $postId,
            ":likerId" => $_SESSION["id"]
        ];
        $stmt->execute($options);
        }else{
        $sql = "INSERT INTO likedPost(post_id,liker)VALUE(:postId,:likerId)";
        $stmt = $this->connect()->prepare($sql);
        $options = [
            ":postId" => $postId,
            ":likerId" => $_SESSION["id"]
        ];
        $stmt->execute($options);
        }

    }

    public function totalLikes($postId){
        $sql = "SELECT * from likedPost WHERE post_id= :postId";
        $stmt = $this->connect()->prepare($sql);
        $options = [
            ":postId" => $postId,
        ];
        $stmt->execute($options);
        $likes = $stmt->fetchAll();
        return $likes;
    }

}