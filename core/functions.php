<?php
require_once 'dbConfig.php';

function sanitizeInput($input) {
	$input = trim($input);
	$input = stripslashes($input);
	$input = htmlspecialchars($input);
	return $input;
}

function getAllServices($pdo) {
    $query = "SELECT * FROM customers";
	$statement = $pdo -> prepare($query);
	$executeQuery = $statement -> execute();
	
    if ($executeQuery) {
		return $statement -> fetchAll();
	}
}

function getAllServicesExceptID($pdo, $customers_id) {
    $query = "SELECT * FROM customers WHERE NOT customers_id = ?";
	$statement = $pdo -> prepare($query);
	$executeQuery = $statement -> execute([$customers_id]);
	
    if ($executeQuery) {
		return $statement -> fetchAll();
	}
}

function getCustomersByID($pdo, $customers_id) {
	$query = "SELECT * FROM customers WHERE customers_id = ?";
	$statement = $pdo -> prepare($query);
	$executeQuery = $statement -> execute([$customers_id]);
	
    if ($executeQuery) {
		return $statement -> fetch();
	}
}


function checkUsernameExistence($pdo, $username) {
	$query = "SELECT * FROM customers_accounts WHERE username = ?";
	$statement = $pdo -> prepare($query);
	$executeQuery = $statement -> execute([$username]);

	if($statement -> rowCount() > 0) {
		return true;
	}
}

function checkUserExistence($pdo, $first_name, $last_name, $age, $gender) {
	$query = "SELECT * FROM customers 
				WHERE first_name = ? AND 
				last_name = ? AND
				age = ? AND
				gender = ?";
	$statement = $pdo -> prepare($query);
	$executeQuery = $statement -> execute([$first_name, $last_name, $age, $gender]);

	if($statement -> rowCount() > 0) {
		return true;
	}
}

function validatePassword($password) {
	if(strlen($password) >= 8) {
		$hasLower = false;
		$hasUpper = false;
		$hasNumber = false;

		for($i = 0; $i < strlen($password); $i++) {
			if(ctype_lower($password[$i])) {
				$hasLower = true;
			}
			if(ctype_upper($password[$i])) {
				$hasUpper = true;
			}
			if(ctype_digit($password[$i])) {
				$hasNumber = true;
			}

			if($hasLower && $hasUpper && $hasNumber) {
				return true;
			}
		}
	}
	return false;
}

function logServiceAction($pdo, $log_desc, $service_id, $customers_id, $done_by) {
	$query = "INSERT INTO services_logs (log_desc, service_id, customers_id, done_by) VALUES (?, ?, ?, ?)";
	$statement = $pdo -> prepare($query);
	$executeQuery = $statement -> execute([$log_desc, $service_id, $customers_id, $done_by]);

	if ($executeQuery) {
		return true;	
	}
}

function getServicesLogs($pdo) {
	$query = "SELECT * FROM services_logs ORDER BY date_logged DESC";
	$statement = $pdo -> prepare($query);
	$executeQuery = $statement -> execute();
	
    if ($executeQuery) {
		return $statement -> fetchAll();
	}
}


function addUser($pdo, $username, $password, $hashed_password, $confirm_password, $first_name, $last_name, $age, $gender) {
	if(checkUsernameExistence($pdo, $username)) {
		return "UsernameAlreadyExists";
	}
	if(checkUserExistence($pdo, $first_name, $last_name, $age, $gender)) {
		return "UserAlreadyExists";
	}
	if($password != $confirm_password) {
		return "PasswordNotMatch";
	}
	if(!validatePassword($password)) {
		return "InvalidPassword";
	}

	$query1 = "INSERT INTO customers_accounts (username, user_password) VALUES (?, ?)";
	$statement1 = $pdo -> prepare($query1);
	$executeQuery1 = $statement1 -> execute([$username, $hashed_password]);

    $query2 = "INSERT INTO customers (first_name, last_name, age, gender) VALUES (?, ?, ?, ?)";
    $statement2 = $pdo -> prepare($query2);
	$executeQuery2 = $statement2 -> execute([$first_name, $last_name, $age, $gender]);
    
    if ($executeQuery1 && $executeQuery2) {
		return "registrationSuccess";	
	}
}

function loginUser($pdo, $username, $password) {
	if(!checkUsernameExistence($pdo, $username)) {
		return "usernameDoesntExist";
	}

	$query = "SELECT * FROM customers_accounts WHERE username = ?";
	$statement = $pdo -> prepare($query);
	$statement -> execute([$username]);
	$userAccInfo = $statement -> fetch();

	if(password_verify($password, $userAccInfo['user_password'])) {
		$_SESSION['customers_id'] = $userAccInfo['customers_id'];
		$_SESSION['username'] = $userAccInfo['username'];
		return "loginSuccess";
	} else {
		return "incorrectPassword";
	}
}


function updateUser($pdo, $first_name, $last_name, $age, $gender, $customers_id) {
	$query = "UPDATE customers
				SET first_name = ?,
                last_name = ?,
                age = ?,
                gender = ?
			WHERE customers_id = ?";
	$statement = $pdo -> prepare($query);
	$executeQuery = $statement -> execute([$first_name, $last_name, $age, $gender, $customers_id]);
	
    if ($executeQuery) {
		return true;
	}
}


function getAllCustomers($pdo) {
    $query = "SELECT 
                customers.*, 
                services.done_by, 
                services.date_logged,
                accounts.username AS done_by_username 
              FROM 
                customers
              LEFT JOIN 
                services ON customers.customers_id = services.customers_id
              LEFT JOIN 
                customers_accounts AS accounts ON services.done_by = accounts.customers_id";
    $statement = $pdo->prepare($query);
    $executeQuery = $statement->execute();

    if ($executeQuery) {
        return $statement->fetchAll();
    }
}

function getCustomerByID($pdo, $customers_id) {
    if (!is_numeric($customers_id)) {
        throw new InvalidArgumentException("Customer ID must be numeric");
    }
    
    $query = "SELECT * FROM customers WHERE customers_id = ?";
    $statement = $pdo->prepare($query);
    
    if ($statement->execute([$customers_id])) {
        return $statement->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    return null;
}

function getServicesByCustomersID($pdo, $customers_id) {
    if (!is_numeric($customers_id)) {
        throw new InvalidArgumentException("Customer ID must be numeric");
    }

    $query = "SELECT * FROM services WHERE customers_id = ?";
    $statement = $pdo->prepare($query);
    $statement->execute([$customers_id]);
    
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

function getServiceByID($pdo, $service_id) {
    if (!is_numeric($service_id)) {
        throw new InvalidArgumentException("Service ID must be numeric");
    }

    $query = "SELECT * FROM services WHERE service_id = ?";
    $statement = $pdo->prepare($query);
    
    if ($statement->execute([$service_id])) {
        return $statement->fetch(PDO::FETCH_ASSOC) ?: null;
    }
    return null;
}

function addCustomer($pdo, $first_name, $last_name, $age, $gender) {
    $query = "INSERT INTO customers (first_name, last_name, age, gender, date_registered) VALUES (?, ?, ?, ?, NOW())";
    $statement = $pdo->prepare($query);
    
    try {
        $executeQuery = $statement->execute([$first_name, $last_name, $age, $gender]);
        return $executeQuery;
    } catch (Exception $e) {
        return false;
    }
}

function updateCustomer($pdo, $first_name, $last_name, $age, $gender, $customers_id) {
    if (!is_numeric($customers_id)) {
        throw new InvalidArgumentException("Customer ID must be numeric");
    }

    $query = "UPDATE customers SET first_name = ?, last_name = ?, age = ?, gender = ? WHERE customers_id = ?";
    $statement = $pdo->prepare($query);
    
    try {
        $executeQuery = $statement->execute([$first_name, $last_name, $age, $gender, $customers_id]);
        return $executeQuery;
    } catch (Exception $e) {
        return false;
    }
}

function removeCustomer($pdo, $customers_id) {
    if (!is_numeric($customers_id)) {
        throw new InvalidArgumentException("Customer ID must be numeric");
    }

    $query = "DELETE FROM customers WHERE customers_id = ?";
    $statement = $pdo->prepare($query);
    
    try {
        $executeQuery = $statement->execute([$customers_id]);
        return $executeQuery;
    } catch (Exception $e) {
        return false;
    }
}

function addService($pdo, $customers_id, $service_name, $service_date) {
    if (!is_numeric($customers_id)) {
        throw new InvalidArgumentException("Customer ID must be numeric");
    }

    $query = "INSERT INTO services (customers_id, service_name, service_date) VALUES (?, ?, ?)";
    $statement = $pdo->prepare($query);
    
    try {
        $executeQuery = $statement->execute([$customers_id, $service_name, $service_date]);
        return $executeQuery;
    } catch (Exception $e) {
        return false;
    }
}

function updateService($pdo, $service_name, $service_date, $service_id) {
    if (!is_numeric($service_id)) {
        throw new InvalidArgumentException("Service ID must be numeric");
    }

    $query = "UPDATE services SET service_name = ?, service_date = ? WHERE service_id = ?";
    $statement = $pdo->prepare($query);
    
    try {
        $executeQuery = $statement->execute([$service_name, $service_date, $service_id]);
        return $executeQuery;
    } catch (Exception $e) {
        return false;
    }
}

function removeService($pdo, $service_id) {
    if (!is_numeric($service_id)) {
        throw new InvalidArgumentException("Service ID must be numeric");
    }

    $query = "DELETE FROM services WHERE service_id = ?";
    $statement = $pdo->prepare($query);
    
    try {
        $executeQuery = $statement->execute([$service_id]);
        return $executeQuery;
    } catch (Exception $e) {
        return false;
    }
}
?>
