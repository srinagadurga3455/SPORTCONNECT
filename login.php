<?php
// Start session for user authentication
session_start();

// Database configuration
$host = 'localhost';
$username = 'root'; // Default for XAMPP
$password = ''; // Default for XAMPP
$dbname = 'sportconnect';

// Connect to DB
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Process login form data
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $phone = $_POST['phone'] ?? '';
    $password = $_POST['password'] ?? '';
    $userType = $_POST['user_type'] ?? '';
    $latitude = $_POST['latitude'] ?? '';
    $longitude = $_POST['longitude'] ?? '';
    
    // Validate required fields
    if (empty($phone) || empty($password)) {
        $response = [
            'success' => false,
            'message' => 'Phone number and password are required'
        ];
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
    }
    
    try {
        // Prepare SQL to find user by phone number and user type
        $stmt = $conn->prepare("SELECT id, first_name, last_name, email, phone, password, user_type, location, sport, skill_level, certification, specialization, experience, turf_name, turf_address, pin_code, contact_person, business_phone, available_sports FROM users WHERE phone = ? AND user_type = ?");
        $stmt->bind_param("ss", $phone, $userType);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            
            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct - create session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_type'] = $user['user_type'];
                $_SESSION['first_name'] = $user['first_name'];
                $_SESSION['last_name'] = $user['last_name'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['phone'] = $user['phone'];
                $_SESSION['location'] = $user['location'];
                
                // Store user type specific data
                if ($user['user_type'] === 'player') {
                    $_SESSION['sport'] = $user['sport'];
                    $_SESSION['skill_level'] = $user['skill_level'];
                } elseif ($user['user_type'] === 'coach') {
                    $_SESSION['certification'] = $user['certification'];
                    $_SESSION['specialization'] = $user['specialization'];
                    $_SESSION['experience'] = $user['experience'];
                } elseif ($user['user_type'] === 'turf') {
                    $_SESSION['turf_name'] = $user['turf_name'];
                    $_SESSION['turf_address'] = $user['turf_address'];
                    $_SESSION['pin_code'] = $user['pin_code'];
                    $_SESSION['contact_person'] = $user['contact_person'];
                    $_SESSION['business_phone'] = $user['business_phone'];
                    $_SESSION['available_sports'] = $user['available_sports'];
                }
                
                // Update user's location if provided
                if (!empty($latitude) && !empty($longitude)) {
                    $updateStmt = $conn->prepare("UPDATE users SET location = CONCAT(?, ', ', ?) WHERE id = ?");
                    $locationStr = "Lat: " . number_format($latitude, 6) . ", Lng: " . number_format($longitude, 6);
                    $updateStmt->bind_param("ssi", $locationStr, $userType, $user['id']);
                    $updateStmt->execute();
                    $updateStmt->close();
                }
                
                // Return success response with user-specific data
                $response = [
                    'success' => true,
                    'message' => 'Login successful!',
                    'user_type' => $user['user_type'],
                    'user_name' => $user['first_name'] . ' ' . $user['last_name'],
                    'redirect_url' => getDashboardUrl($user['user_type'])
                ];
                
                // Add user type specific data to response
                if ($user['user_type'] === 'player') {
                    $response['sport'] = $user['sport'];
                    $response['skill_level'] = $user['skill_level'];
                } elseif ($user['user_type'] === 'coach') {
                    $response['certification'] = $user['certification'];
                    $response['specialization'] = $user['specialization'];
                    $response['experience'] = $user['experience'];
                } elseif ($user['user_type'] === 'turf') {
                    $response['turf_name'] = $user['turf_name'];
                    $response['turf_address'] = $user['turf_address'];
                    $response['pin_code'] = $user['pin_code'];
                    $response['contact_person'] = $user['contact_person'];
                    $response['business_phone'] = $user['business_phone'];
                    $response['available_sports'] = $user['available_sports'];
                }
                
                header('Content-Type: application/json');
                echo json_encode($response);
                exit();
                
            } else {
                // Password is incorrect
                $response = [
                    'success' => false,
                    'message' => 'Invalid phone number or password'
                ];
                
                header('Content-Type: application/json');
                echo json_encode($response);
                exit();
            }
            
        } else {
            // User not found
            $response = [
                'success' => false,
                'message' => 'No account found with this phone number and user type'
            ];
            
            header('Content-Type: application/json');
            echo json_encode($response);
            exit();
        }
        
        $stmt->close();
        
    } catch (Exception $e) {
        $response = [
            'success' => false,
            'message' => 'Login error: ' . $e->getMessage()
        ];
        
        header('Content-Type: application/json');
        http_response_code(500);
        echo json_encode($response);
        exit();
    }
    
} else {
    // Return error for non-POST requests
    $response = [
        'success' => false,
        'message' => 'Invalid request method'
    ];
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Function to get appropriate dashboard URL based on user type
function getDashboardUrl($userType) {
    switch ($userType) {
        case 'player':
            return 'player-dashboard.html';
        case 'coach':
            return 'coach-dashboard.html';
        case 'turf':
            return 'turf-dashboard.html';
        default:
            return 'index.html';
    }
}

$conn->close();
?>