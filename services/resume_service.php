<?php
require_once 'service.php';

class ResumeService extends BaseService {
    public function getPersonalInfo() {
        $query = "SELECT * FROM personal_info LIMIT 1";
        return $this->fetchOne($query);
    }

    public function getKeyAchievements() {
        $query = "SELECT * FROM key_achievements WHERE is_active = TRUE ORDER BY display_order ASC";
        return $this->fetchAll($query);
    }

    public function getProfessionalGoals() {
        $query = "SELECT * FROM professional_goals WHERE is_completed = FALSE ORDER BY display_order ASC";
        return $this->fetchAll($query);
    }

    public function getTechnicalSkills() {
        $query = "SELECT * FROM technical_skills ORDER BY display_order ASC";
        return $this->fetchAll($query);
    }

    public function getWorkExperience() {
        $query = "SELECT * FROM work_experience ORDER BY display_order ASC, start_date DESC";
        return $this->fetchAll($query);
    }

    public function getWorkAchievements($work_id) {
        $work_id = (int)$work_id;
        $query = "SELECT * FROM work_achievements WHERE work_id = $work_id ORDER BY display_order ASC";
        return $this->fetchAll($query);
    }

    public function getCertifications() {
        $query = "SELECT * FROM certifications ORDER BY display_order ASC, issue_date DESC";
        return $this->fetchAll($query);
    }

    public function getLanguages() {
        $query = "SELECT * FROM languages ORDER BY display_order ASC";
        return $this->fetchAll($query);
    }

    public function getProjectFeatures($project_id) {
        $project_id = (int)$project_id;
        $query = "SELECT * FROM project_features WHERE project_id = $project_id ORDER BY display_order ASC";
        return $this->fetchAll($query);
    }

    public function getProjectTestimonials($project_id) {
        $project_id = (int)$project_id;
        $query = "SELECT * FROM project_testimonials WHERE project_id = $project_id ORDER BY display_order ASC";
        return $this->fetchAll($query);
    }

    public function getTechnicalTools($skill_id) {
        $skill_id = (int)$skill_id;
        $query = "SELECT * FROM technical_tools WHERE skill_id = $skill_id ORDER BY proficiency DESC";
        return $this->fetchAll($query);
    }

    public function getProfessionalReferences() {
        $query = "SELECT * FROM professional_references WHERE is_public = TRUE";
        return $this->fetchAll($query);
    }
}
?>