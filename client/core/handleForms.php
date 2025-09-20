<?php  
require_once '../classloader.php';

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'fiverr_administrator';
}


if (isset($_POST['insertNewUserBtn'])) {
	$username = htmlspecialchars(trim($_POST['username']));
	$email = htmlspecialchars(trim($_POST['email']));
	$contact_number = htmlspecialchars(trim($_POST['contact_number']));
	$password = trim($_POST['password']);
	$confirm_password = trim($_POST['confirm_password']);

	if (!empty($username) && !empty($email) && !empty($password) && !empty($confirm_password)) {

		if ($password == $confirm_password) {

			if (!$userObj->usernameExists($username)) {

				if ($userObj->registerUser($username, $email, $password, $contact_number)) {
					header("Location: ../login.php");
					exit();
				}

				else {
					$_SESSION['message'] = "An error occured with the query!";
					$_SESSION['status'] = '400';
					header("Location: ../register.php");
					exit();
				}
			}

			else {
				$_SESSION['message'] = $username . " as username is already taken";
				$_SESSION['status'] = '400';
				header("Location: ../register.php");
				exit();
			}
		}
		else {
			$_SESSION['message'] = "Please make sure both passwords are equal";
			$_SESSION['status'] = '400';
			header("Location: ../register.php");
			exit();
		}
	}
	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../register.php");
		exit();
	}
}

if (isset($_POST['loginUserBtn'])) {
	$email = trim($_POST['email']);
	$password = trim($_POST['password']);

	if (!empty($email) && !empty($password)) {
		// The updated loginUser method will set the user_role in the session
		if ($userObj->loginUser($email, $password)) {
			// Redirect to the main index, which will then redirect based on role
			header("Location: ../index.php");
			exit();
		}
		else {
			$_SESSION['message'] = "Username/password invalid";
			$_SESSION['status'] = "400";
			header("Location: ../login.php");
			exit();
		}
	}

	else {
		$_SESSION['message'] = "Please make sure there are no empty input fields";
		$_SESSION['status'] = '400';
		header("Location: ../login.php");
		exit();
	}

}

if (isset($_GET['logoutUserBtn'])) {
	$userObj->logout();
	header("Location: ../index.php");
	exit();
}

if (isset($_POST['addCategoryBtn'])) {
    if (!isAdmin()) {
        $_SESSION['message'] = "You are not authorized to perform this action.";
        $_SESSION['status'] = '403';
        header("Location: ../index.php");
        exit();
    }
    $category_name = htmlspecialchars(trim($_POST['category_name']));
    if (!empty($category_name) && $categoryObj->createCategory($category_name)) {
        $_SESSION['message'] = "Category created successfully!";
        $_SESSION['status'] = '200';
    } else {
        $_SESSION['message'] = "Failed to create category (it might already exist).";
        $_SESSION['status'] = '400';
    }
    header("Location: /admin/admin_dashboard.php");
    exit();
}

if (isset($_POST['addSubcategoryBtn'])) {
    if (!isAdmin()) {
        $_SESSION['message'] = "You are not authorized to perform this action.";
        $_SESSION['status'] = '403';
        header("Location: ../index.php");
        exit();
    }
    $category_id = intval($_POST['category_id']);
    $subcategory_name = htmlspecialchars(trim($_POST['subcategory_name']));
    if ($category_id > 0 && !empty($subcategory_name) && $categoryObj->createSubcategory($category_id, $subcategory_name)) {
        $_SESSION['message'] = "Subcategory created successfully!";
        $_SESSION['status'] = '200';
    } else {
        $_SESSION['message'] = "Failed to create subcategory. Please check your inputs.";
        $_SESSION['status'] = '400';
    }
    header("Location: /admin/admin_dashboard.php");
    exit();
}

if (isset($_POST['insertProposalBtn'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $description = htmlspecialchars(trim($_POST['description']));
    $min_price = intval($_POST['min_price']);
    $max_price = intval($_POST['max_price']);
    $category_id = intval($_POST['category_id']);
    $subcategory_id = intval($_POST['subcategory_id']);
    $image_name = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $target_dir = "../images/";
        $image_name = basename($_FILES["image"]["name"]);
        $target_file = $target_dir . $image_name;
        move_uploaded_file($_FILES["image"]["tmp_name"], $target_file);
    }
    
    if ($proposalObj->createProposal($user_id, $description, $image_name, $min_price, $max_price, $category_id, $subcategory_id)) {
        $_SESSION['message'] = "Proposal created successfully!";
        $_SESSION['status'] = '200';
    } else {
        $_SESSION['message'] = "Failed to create the proposal.";
        $_SESSION['status'] = '500';
    }
    header("Location: ../freelancer/index.php");
    exit();
}

if (isset($_POST['updateUserBtn'])) {
	$contact_number = htmlspecialchars($_POST['contact_number']);
	$bio_description = htmlspecialchars($_POST['bio_description']);
	if ($userObj->updateUser($contact_number, $bio_description, $_SESSION['user_id'])) {
		header("Location: ../profile.php");
		exit();
	}
}

if (isset($_POST['insertOfferBtn'])) {
	$user_id = $_SESSION['user_id'];
	$proposal_id = $_POST['proposal_id'];
	$description = htmlspecialchars($_POST['description']);

	if ($offerObj->hasUserOffered($user_id, $proposal_id)) {
		$_SESSION['message'] = "You have already submitted an offer for this proposal.";
		$_SESSION['status'] = '400';
		header("Location: ../proposal-details.php?proposal_id=" . $proposal_id);
		exit();
	}

	if ($offerObj->createOffer($user_id, $description, $proposal_id)) {
		$_SESSION['message'] = "Your offer was submitted successfully!";
		$_SESSION['status'] = '200';
		header("Location: ../index.php");
		exit();
	} else {
		$_SESSION['message'] = "An error occurred while submitting your offer.";
		$_SESSION['status'] = '500';
		header("Location: ../proposal-details.php?proposal_id=" . $proposal_id);
		exit();
	}
}

if (isset($_POST['updateOfferBtn'])) {
	$description = htmlspecialchars($_POST['description']);
	$offer_id = $_POST['offer_id'];
	if ($offerObj->updateOffer($description, $offer_id)) {
		$_SESSION['message'] = "Offer updated successfully!";
		$_SESSION['status'] = '200';
		header("Location: ../index.php");
		exit();
	}
}

if (isset($_POST['deleteOfferBtn'])) {
	$offer_id = $_POST['offer_id'];
	if ($offerObj->deleteOffer($offer_id)) {
		$_SESSION['message'] = "Offer deleted successfully!";
		$_SESSION['status'] = '200';
		header("Location: ../index.php");
		exit();
	}
}

if (isset($_POST['deleteCategoryBtn'])) {
    if (!isAdmin()) {
        header("Location: ../../index.php"); exit();
    }
    $category_id = intval($_POST['category_id']);
    if ($categoryObj->deleteCategory($category_id)) {
        $_SESSION['message'] = "Category deleted successfully.";
        $_SESSION['status'] = '200';
    } else {
        $_SESSION['message'] = "Failed to delete category.";
        $_SESSION['status'] = '400';
    }
    header("Location: ../../admin/admin_dashboard.php");
    exit();
}

if (isset($_POST['deleteSubcategoryBtn'])) {
    if (!isAdmin()) {
        header("Location: ../../index.php"); exit();
    }
    $subcategory_id = intval($_POST['subcategory_id']);
    if ($categoryObj->deleteSubcategory($subcategory_id)) {
        $_SESSION['message'] = "Subcategory deleted successfully.";
        $_SESSION['status'] = '200';
    } else {
        $_SESSION['message'] = "Failed to delete subcategory.";
        $_SESSION['status'] = '400';
    }
    header("Location: ../../admin/admin_dashboard.php");
    exit();
}

?>