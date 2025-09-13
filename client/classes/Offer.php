<?php  
/**
 * Class for handling Offer-related operations.
 * Inherits CRUD methods from the Database class.
 */
class Offer extends Database {

    /**
     * Creates a new Offer.
     * @param int $user_id The ID of the user submitting the offer.
     * @param string $description The content of the offer.
     * @param int $proposal_id The ID of the proposal.
     * @return int The ID of the newly created Offer.
     */
    public function createOffer($user_id, $description, $proposal_id) {
        $sql = "INSERT INTO offers (user_id, description, proposal_id) VALUES (?, ?, ?)";
        return $this->executeNonQuery($sql, [$user_id, $description, $proposal_id]);
    }

    /**
     * Retrieves offers from the database.
     * @param int|null $offer_id The Offer ID to retrieve, or null for all offers.
     * @return array
     */
    public function getOffers($offer_id = null) {
        if ($offer_id) {
            $sql = "SELECT * FROM offers WHERE offer_id = ?";
            return $this->executeQuerySingle($sql, [$offer_id]);
        }
        $sql = "SELECT 
                    offers.*, fiverr_clone_users.*, 
                    offers.date_added AS offer_date_added
                FROM offers JOIN fiverr_clone_users ON 
                offers.user_id = fiverr_clone_users.user_id 
                ORDER BY offers.date_added DESC";
        return $this->executeQuery($sql);
    }

    /**
     * Retrieves all offers for a specific proposal.
     * @param int $proposal_id The ID of the proposal.
     * @return array
     */
    public function getOffersByProposalID($proposal_id) {
        $sql = "SELECT 
                    offers.*, fiverr_clone_users.*, 
                    offers.date_added AS offer_date_added 
                FROM Offers 
                JOIN fiverr_clone_users ON 
                    offers.user_id = fiverr_clone_users.user_id
                WHERE proposal_id = ? 
                ORDER BY Offers.date_added DESC";
        return $this->executeQuery($sql, [$proposal_id]);
    }

    /**
     * Checks if a user has already submitted an offer for a specific proposal.
     * @param int $user_id The ID of the user.
     * @param int $proposal_id The ID of the proposal.
     * @return bool True if an offer exists, false otherwise.
     */
    public function hasUserOffered($user_id, $proposal_id) {
        $sql = "SELECT COUNT(*) as count FROM offers WHERE user_id = ? AND proposal_id = ?";
        $result = $this->executeQuerySingle($sql, [$user_id, $proposal_id]);
        return $result && $result['count'] > 0;
    }

    /**
     * Updates an Offer.
     * @param string $description The new content for the offer.
     * @param int $offer_id The ID of the offer to update.
     * @return int The number of affected rows.
     */
    public function updateOffer($description, $offer_id) {
        $sql = "UPDATE Offers SET description = ? WHERE Offer_id = ?";
        return $this->executeNonQuery($sql, [$description, $offer_id]);
    }
    
    /**
     * Deletes an Offer.
     * @param int $id The Offer ID to delete.
     * @return int The number of affected rows.
     */
    public function deleteOffer($id) {
        $sql = "DELETE FROM Offers WHERE Offer_id = ?";
        return $this->executeNonQuery($sql, [$id]);
    }
}
?>