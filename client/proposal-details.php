<?php 
require_once 'classloader.php'; 
// If you have a standard header file, you would include it here.
// For example: require_once 'includes/header.php'; 

// --- VALIDATION AND DATA FETCHING ---

// 1. Ensure a proposal ID is provided in the URL and it's a number.
if (!isset($_GET['proposal_id']) || !is_numeric($_GET['proposal_id'])) {
    header("Location: index.php"); // Redirect if ID is missing
    exit();
}

$proposal_id = (int)$_GET['proposal_id'];

// 2. Create objects for Proposals and Offers.
$proposalObj = new Proposal();
$offerObj = new Offer();

// 3. Fetch the details for this specific proposal.
$proposal = $proposalObj->getProposals($proposal_id);

// 4. Fetch all the existing offers for this proposal to display them.
$offers = $offerObj->getOffersByProposalID($proposal_id);

// 5. If no proposal with that ID exists, redirect to the home page.
if (!$proposal) {
    $_SESSION['message'] = "The proposal you are looking for does not exist.";
    $_SESSION['status'] = '404'; // Use a different status for "not found"
    header("Location: index.php");
    exit();
}

?>
<!doctype html>
<html lang="en">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <title>Proposal Details</title>
    <style>
      body { font-family: "Arial", sans-serif; background-color: #f4f4f4; }
      .container { padding-top: 30px; }
      .card { margin-bottom: 20px; }
    </style>
</head>
<body>
    <?php include 'includes/navbar.php'; // Assuming you have a navbar include ?>

    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <?php if (isset($_SESSION['message'])): ?>
                    <div class="alert <?= $_SESSION['status'] == '400' ? 'alert-danger' : 'alert-success' ?>" role="alert">
                        <strong><?= htmlspecialchars($_SESSION['message']) ?></strong>
                    </div>
                    <?php 
                        // Unset the message so it doesn't show again on refresh
                        unset($_SESSION['message']);
                        unset($_SESSION['status']);
                    ?>
                <?php endif; ?>

                <!-- Proposal Details -->
                <div class="card">
                    <div class="card-header">
                        <h4>Project Proposal</h4>
                    </div>
                    <div class="card-body">
                        <p class="text-muted">Posted by: <strong><?= htmlspecialchars($proposal['username']) ?></strong></p>
                        <h5>Budget Range</h5>
                        <p>$<?= htmlspecialchars($proposal['min_price']) ?> - $<?= htmlspecialchars($proposal['max_price']) ?></p>
                        <hr>
                        <h5>Project Description</h5>
                        <p><?= nl2br(htmlspecialchars($proposal['description'])) ?></p>
                    </div>
                    <div class="card-footer text-muted">
                        Posted on: <?= date("F j, Y", strtotime($proposal['proposals_date_added'])) ?>
                    </div>
                </div>

                <!-- Offer Submission Form -->
                <?php if ($userObj->isLoggedIn()): ?>
                    <div class="card">
                        <div class="card-header">
                            <h5>Submit Your Offer</h5>
                        </div>
                        <div class="card-body">
                            <form action="core/handleForms.php" method="POST">
                                <input type="hidden" name="proposal_id" value="<?= $proposal_id ?>">
                                <div class="form-group">
                                    <label for="description">Describe your proposal:</label>
                                    <textarea name="description" class="form-control" rows="6" required placeholder="Explain why you are the best fit for this project..."></textarea>
                                </div>
                                <button type="submit" name="insertOfferBtn" class="btn btn-primary">Submit Offer</button>
                            </form>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        You must be <a href="login.php">logged in</a> to submit an offer.
                    </div>
                <?php endif; ?>

                <!-- Display Existing Offers -->
                <div class="card">
                    <div class="card-header">
                        <h5>Existing Offers (<?= count($offers) ?>)</h5>
                    </div>
                    <ul class="list-group list-group-flush">
                        <?php if (count($offers) > 0): ?>
                            <?php foreach ($offers as $offer): ?>
                                <li class="list-group-item">
                                    <strong><?= htmlspecialchars($offer['username']) ?></strong>
                                    <p class="mt-2"><?= nl2br(htmlspecialchars($offer['description'])) ?></p>
                                    <small class="text-muted">Submitted on: <?= date("F j, Y", strtotime($offer['offer_date_added'])) ?></small>
                                </li>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li class="list-group-item">There are no offers for this project yet.</li>
                        <?php endif; ?>
                    </ul>
                </div>

            </div>
        </div>
    </div>
</body>
</html>