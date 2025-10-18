<?php
require_once 'service.php';

class PortfolioService extends BaseService {
    public function getAllProjects($orderBy = 'display_order') {
        $validOrders = ['display_order', 'project_date', 'popularity'];
        $orderBy = in_array($orderBy, $validOrders) ? $orderBy : 'display_order';
        $orderBy = $this->db->real_escape_string($orderBy);
        
        $query = "SELECT p.*, 
                 GROUP_CONCAT(DISTINCT f.feature ORDER BY f.display_order SEPARATOR '|||') as features,
                 GROUP_CONCAT(DISTINCT t.technology ORDER BY t.display_order SEPARATOR ',') as technologies,
                 GROUP_CONCAT(DISTINCT cs.language, ':::', cs.code ORDER BY cs.display_order SEPARATOR '|||') as code_samples
                 FROM projects p
                 LEFT JOIN project_features f ON p.id = f.project_id
                 LEFT JOIN project_technologies t ON p.id = t.project_id
                 LEFT JOIN code_samples cs ON p.id = cs.project_id
                 GROUP BY p.id
                 ORDER BY $orderBy ASC";
        
        return $this->fetchAll($query);
    }

    public function getFeaturedProjects() {
        $query = "SELECT * FROM projects WHERE is_featured = TRUE ORDER BY display_order ASC, popularity DESC";
        return $this->fetchAll($query);
    }
}
?>