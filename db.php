<?php
/**
 * Livora database bootstrap.
 * Configure through environment variables for Docker, AWS EC2, or Amazon RDS.
 */
function env_value(string $key, string $default = ''): string {
    $value = getenv($key);
    return ($value === false || $value === '') ? $default : $value;
}

define('DB_HOST', env_value('DB_HOST', '127.0.0.1'));
define('DB_USER', env_value('DB_USER', 'root'));
define('DB_PASS', env_value('DB_PASS', ''));
define('DB_NAME', env_value('DB_NAME', 'livora_db'));
define('DB_PORT', (int) env_value('DB_PORT', '3306'));

function getDB(): ?mysqli {
    mysqli_report(MYSQLI_REPORT_OFF);
    $conn = @new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    if ($conn->connect_error) {
        error_log('Livora database connection failed: ' . $conn->connect_error);
        return null;
    }
    $conn->set_charset('utf8mb4');
    return $conn;
}

function getSampleRoommates() {
    $conn = getDB();
    if ($conn) {
        $result = $conn->query("SELECT r.*, u.name, u.profile_img, u.id AS owner_id FROM roommates r JOIN users u ON u.id=r.user_id WHERE r.active=1 ORDER BY r.created_at DESC");
        if ($result && $result->num_rows > 0) {
            $rows = [];
            while ($r = $result->fetch_assoc()) {
                $rows[] = ['id'=>(int)$r['id'],'owner_id'=>(int)$r['owner_id'],'name'=>$r['name'],
                    'city'=>$r['city'],'area'=>$r['area'],'budget'=>(int)$r['budget'],
                    'gender'=>$r['gender'],'age'=>$r['age'],'occupation'=>$r['occupation'],
                    'bio'=>$r['bio'],'img'=>$r['profile_img'] ? 'listing_image.php?user='.(int)$r['owner_id'] : 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=300&h=300&fit=crop',
                    'rating'=>(float)$r['rating'],'verified'=>(int)$r['verified'],'tags'=>$r['tags'] ?? ''];
            }
            $conn->close();
            return $rows;
        }
        $conn->close();
    }
    return [
        ['id'=>1,'name'=>'Priya Sharma','city'=>'Mumbai','area'=>'Bandra','budget'=>8000,'gender'=>'Female','age'=>25,'occupation'=>'Software Engineer','bio'=>'Clean, non-smoker, early riser. Looking for female roommate.','img'=>'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=300&h=300&fit=crop','rating'=>4.8,'verified'=>1,'tags'=>'Non-smoker,Vegetarian,Pet-friendly'],
        ['id'=>2,'name'=>'Kiran Patel','city'=>'Pune','area'=>'Koregaon Park','budget'=>6000,'gender'=>'Male','age'=>27,'occupation'=>'MBA Student','bio'=>'Friendly and responsible. Love cooking and reading.','img'=>'https://images.unsplash.com/photo-1539571696357-5a69c17a67c6?w=300&h=300&fit=crop','rating'=>4.6,'verified'=>1,'tags'=>'Students OK,Non-smoker,Music lover'],
        ['id'=>3,'name'=>'Aisha Khan','city'=>'Delhi','area'=>'Connaught Place','budget'=>10000,'gender'=>'Female','age'=>28,'occupation'=>'Journalist','bio'=>'Independent and respectful. Love my space but enjoy good company.','img'=>'https://images.unsplash.com/photo-1531746020798-e6953c6e8e04?w=300&h=300&fit=crop','rating'=>4.9,'verified'=>1,'tags'=>'Working Professional,Quiet Hours,Non-smoker'],
        ['id'=>4,'name'=>'Rohan Verma','city'=>'Bangalore','area'=>'Koramangala','budget'=>9000,'gender'=>'Male','age'=>24,'occupation'=>'UI Designer','bio'=>'Creative, late worker, enjoys weekend cooking.','img'=>'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?w=300&h=300&fit=crop','rating'=>4.5,'verified'=>1,'tags'=>'Night Owl,Creative,Techie'],
        ['id'=>5,'name'=>'Neha Gupta','city'=>'Mumbai','area'=>'Andheri','budget'=>7500,'gender'=>'Female','age'=>26,'occupation'=>'Nurse','bio'=>'Quiet and clean. Work odd hours, need understanding roommate.','img'=>'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?w=300&h=300&fit=crop','rating'=>4.7,'verified'=>1,'tags'=>'Working Professional,Quiet,Night Shifts'],
        ['id'=>6,'name'=>'Amit Singh','city'=>'Hyderabad','area'=>'Hitech City','budget'=>8500,'gender'=>'Male','age'=>29,'occupation'=>'IT Professional','bio'=>'Fitness enthusiast, vegetarian, always on time with rent.','img'=>'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?w=300&h=300&fit=crop','rating'=>4.8,'verified'=>1,'tags'=>'Vegetarian,Fitness,Non-drinker'],
    ];
}

function getSampleCooks() {
    $conn = getDB();
    if ($conn) {
        $result = $conn->query("SELECT c.*, u.name, u.profile_img, u.id AS owner_id FROM cooks c JOIN users u ON u.id=c.user_id WHERE c.active=1 ORDER BY c.created_at DESC");
        if ($result && $result->num_rows > 0) {
            $rows = [];
            while ($r = $result->fetch_assoc()) {
                $rows[] = ['id'=>(int)$r['id'],'owner_id'=>(int)$r['owner_id'],'name'=>$r['name'],
                    'city'=>$r['city'],'area'=>$r['area'],'charge'=>(int)$r['charge'],
                    'specialty'=>$r['specialty'],'experience'=>(int)$r['experience'],
                    'bio'=>$r['bio'],'img'=>$r['profile_img'] ? 'listing_image.php?user='.(int)$r['owner_id'] : 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=300&h=300&fit=crop',
                    'rating'=>(float)$r['rating'],'verified'=>(int)$r['verified'],'availability'=>$r['availability'],'tags'=>$r['tags'] ?? ''];
            }
            $conn->close();
            return $rows;
        }
        $conn->close();
    }
    return [
        ['id'=>1,'name'=>'Sunita Nair','city'=>'Chennai','area'=>'T Nagar','charge'=>4500,'specialty'=>'South Indian','experience'=>8,'img'=>'https://images.unsplash.com/photo-1544005313-94ddf0286df2?w=300&h=300&fit=crop','rating'=>5.0,'verified'=>1,'availability'=>'Full Time','tags'=>'South Indian,Vegan,Diabetic Friendly'],
        ['id'=>2,'name'=>'Rahul Mehta','city'=>'Delhi','area'=>'Karol Bagh','charge'=>5000,'specialty'=>'North Indian','experience'=>10,'img'=>'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=300&h=300&fit=crop','rating'=>4.9,'verified'=>1,'availability'=>'Part Time','tags'=>'North Indian,Mughlai,Sweets'],
        ['id'=>3,'name'=>'Mary Thomas','city'=>'Kochi','area'=>'Marine Drive','charge'=>4000,'specialty'=>'Kerala Cuisine','experience'=>6,'img'=>'https://images.unsplash.com/photo-1580489944761-15a19d654956?w=300&h=300&fit=crop','rating'=>4.8,'verified'=>1,'availability'=>'Full Time','tags'=>'Kerala,Seafood,Tiffin Service'],
        ['id'=>4,'name'=>'Geeta Yadav','city'=>'Mumbai','area'=>'Dadar','charge'=>6000,'specialty'=>'Multi-Cuisine','experience'=>12,'img'=>'https://images.unsplash.com/photo-1567532939604-b6b5b0db2604?w=300&h=300&fit=crop','rating'=>4.7,'verified'=>1,'availability'=>'Full Time','tags'=>'Multi-cuisine,Baking,Parties'],
        ['id'=>5,'name'=>'Shankar Reddy','city'=>'Hyderabad','area'=>'Banjara Hills','charge'=>5500,'specialty'=>'Hyderabadi','experience'=>15,'img'=>'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=300&h=300&fit=crop','rating'=>4.9,'verified'=>1,'availability'=>'Part Time','tags'=>'Biryani,Hyderabadi,Dum Cooking'],
        ['id'=>6,'name'=>'Kavita Joshi','city'=>'Pune','area'=>'Aundh','charge'=>3500,'specialty'=>'Maharashtrian','experience'=>5,'img'=>'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?w=300&h=300&fit=crop','rating'=>4.6,'verified'=>1,'availability'=>'Part Time','tags'=>'Maharashtrian,Healthy,Weight Loss Meals'],
    ];
}

function getSampleMaids() {
    $conn = getDB();
    if ($conn) {
        $result = $conn->query("SELECT m.*, u.name, u.profile_img, u.id AS owner_id FROM maids m JOIN users u ON u.id=m.user_id WHERE m.active=1 ORDER BY m.created_at DESC");
        if ($result && $result->num_rows > 0) {
            $rows = [];
            while ($r = $result->fetch_assoc()) {
                $rows[] = ['id'=>(int)$r['id'],'owner_id'=>(int)$r['owner_id'],'name'=>$r['name'],
                    'city'=>$r['city'],'area'=>$r['area'],'charge'=>(int)$r['charge'],'maid_charge'=>(int)$r['charge'],
                    'experience'=>(int)$r['experience'],'bio'=>$r['bio'],'img'=>$r['profile_img'] ? 'listing_image.php?user='.(int)$r['owner_id'] : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=300&h=300&fit=crop',
                    'rating'=>(float)$r['rating'],'verified'=>(int)$r['verified'],'availability'=>$r['availability'],'tags'=>$r['tags'] ?? ''];
            }
            $conn->close();
            return $rows;
        }
        $conn->close();
    }
    return [
        ['id'=>1,'name'=>'Anita Rao','city'=>'Bangalore','area'=>'Whitefield','charge'=>3500,'experience'=>7,'img'=>'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=300&h=300&fit=crop','rating'=>4.7,'verified'=>1,'availability'=>'Full Time','tags'=>'ID Verified,Sweeping,Mopping,Dishes'],
        ['id'=>2,'name'=>'Lakshmi Devi','city'=>'Chennai','area'=>'Anna Nagar','charge'=>3000,'experience'=>10,'img'=>'https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?w=300&h=300&fit=crop','rating'=>4.8,'verified'=>1,'availability'=>'Part Time','tags'=>'Background Checked,Laundry,Ironing'],
        ['id'=>3,'name'=>'Fatima Begum','city'=>'Mumbai','area'=>'Powai','charge'=>4000,'experience'=>8,'img'=>'https://images.unsplash.com/photo-1508214751196-bcfd4ca60f91?w=300&h=300&fit=crop','rating'=>4.9,'verified'=>1,'availability'=>'Full Time','tags'=>'Police Verified,Deep Cleaning,Baby Care'],
        ['id'=>4,'name'=>'Savitri Ben','city'=>'Ahmedabad','area'=>'Satellite','charge'=>2800,'experience'=>5,'img'=>'https://images.unsplash.com/photo-1548142813-c348350df52b?w=300&h=300&fit=crop','rating'=>4.5,'verified'=>1,'availability'=>'Daily','tags'=>'Sweeping,Utensils,Cooking Help'],
        ['id'=>5,'name'=>'Pushpa Kumari','city'=>'Delhi','area'=>'Dwarka','charge'=>3800,'experience'=>12,'img'=>'https://images.unsplash.com/photo-1607746882042-944635dfe10e?w=300&h=300&fit=crop','rating'=>4.8,'verified'=>1,'availability'=>'Full Time','tags'=>'Experienced,Trustworthy,Full Home Care'],
        ['id'=>6,'name'=>'Rani Devi','city'=>'Kolkata','area'=>'Salt Lake','charge'=>3200,'experience'=>6,'img'=>'https://images.unsplash.com/photo-1491349174775-aaaefdd81942?w=300&h=300&fit=crop','rating'=>4.6,'verified'=>1,'availability'=>'Part Time','tags'=>'References Available,Cleaning,Organizing'],
    ];
}
?>
