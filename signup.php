<?php
// Database configuration
$host = 'localhost';
$dbname = 'sportconnect';
$username = 'root';
$password = ''; // Default XAMPP password is empty

try {
    // Connect to database
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Process form data
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get common form data
        $userType = $_POST['user_type'] ?? '';
        $firstName = $_POST['first_name'] ?? '';
        $lastName = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        
        // Validate required fields
        if (empty($firstName) || empty($lastName) || empty($email) || empty($phone) || empty($password)) {
            throw new Exception("All required fields must be filled");
        }
        
        // Validate email format
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
        
        // Validate password confirmation
        if ($password !== $confirmPassword) {
            throw new Exception("Passwords do not match");
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        // Prepare SQL based on user type
        if ($userType === 'player') {
            $location = $_POST['player_location'] ?? '';
            $sport = $_POST['player_sport'] ?? '';
            $skillLevel = $_POST['player_skill'] ?? '';
            
            $stmt = $conn->prepare("INSERT INTO users (user_type, first_name, last_name, email, phone, password, location, sport, skill_level, created_at) 
                                   VALUES (:user_type, :first_name, :last_name, :email, :phone, :password, :location, :sport, :skill_level, NOW())");
            
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':sport', $sport);
            $stmt->bindParam(':skill_level', $skillLevel);
            
        } elseif ($userType === 'coach') {
            $location = $_POST['coach_location'] ?? '';
            $certification = $_POST['coach_certification'] ?? '';
            $specialization = $_POST['coach_specialization'] ?? '';
            $experience = $_POST['coach_experience'] ?? '';
            
            $stmt = $conn->prepare("INSERT INTO users (user_type, first_name, last_name, email, phone, password, location, certification, specialization, experience, created_at) 
                                   VALUES (:user_type, :first_name, :last_name, :email, :phone, :password, :location, :certification, :specialization, :experience, NOW())");
            
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':certification', $certification);
            $stmt->bindParam(':specialization', $specialization);
            $stmt->bindParam(':experience', $experience);
            
        } elseif ($userType === 'turf') {
            $turfName = $_POST['turf_name'] ?? '';
            $turfAddress = $_POST['turf_address'] ?? '';
            $location = $_POST['turf_location'] ?? '';
            $pinCode = $_POST['turf_pin_code'] ?? '';
            $contactPerson = $_POST['turf_contact_person'] ?? '';
            $businessPhone = $_POST['turf_business_phone'] ?? '';
            $availableSports = implode(', ', $_POST['turf_sports'] ?? []);
            
            $stmt = $conn->prepare("INSERT INTO users (user_type, first_name, last_name, email, phone, password, turf_name, turf_address, location, pin_code, contact_person, business_phone, available_sports, created_at) 
                                   VALUES (:user_type, :first_name, :last_name, :email, :phone, :password, :turf_name, :turf_address, :location, :pin_code, :contact_person, :business_phone, :available_sports, NOW())");
            
            $stmt->bindParam(':turf_name', $turfName);
            $stmt->bindParam(':turf_address', $turfAddress);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':pin_code', $pinCode);
            $stmt->bindParam(':contact_person', $contactPerson);
            $stmt->bindParam(':business_phone', $businessPhone);
            $stmt->bindParam(':available_sports', $availableSports);
            
        } else {
            throw new Exception("Invalid user type");
        }
        
        // Bind common parameters
        $stmt->bindParam(':user_type', $userType);
        $stmt->bindParam(':first_name', $firstName);
        $stmt->bindParam(':last_name', $lastName);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->bindParam(':password', $hashedPassword);
        
        // Execute the statement
        $stmt->execute();
        
        // Return success response with user data
        $response = [
            'success' => true,
            'message' => 'Account created successfully!',
            'user_type' => $userType,
            'user_data' => [
                'user_type' => $userType,
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $email,
                'phone' => $phone,
                'location' => $location ?? '',
                // Role-specific data
                ...($userType === 'player' ? [
                    'sport' => $sport ?? '',
                    'skill_level' => $skillLevel ?? ''
                ] : []),
                ...($userType === 'coach' ? [
                    'certification' => $certification ?? '',
                    'specialization' => $specialization ?? '',
                    'experience' => $experience ?? ''
                ] : []),
                ...($userType === 'turf' ? [
                    'turf_name' => $turfName ?? '',
                    'turf_address' => $turfAddress ?? '',
                    'pin_code' => $pinCode ?? '',
                    'contact_person' => $contactPerson ?? '',
                    'business_phone' => $businessPhone ?? '',
                    'available_sports' => $availableSports ?? ''
                ] : [])
            ]
        ];
        
        header('Content-Type: application/json');
        echo json_encode($response);
        exit();
        
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
    
} catch(PDOException $e) {
    // Database error
    $response = [
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ];
    
    header('Content-Type: application/json');
    http_response_code(500);
    echo json_encode($response);
    exit();
    
} catch(Exception $e) {
    // Validation or other error
    $response = [
        'success' => false,
        'message' => $e->getMessage()
    ];
    
    header('Content-Type: application/json');
    http_response_code(400);
    echo json_encode($response);
    exit();
}
?>