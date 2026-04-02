<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseService.php';

class ResumeService extends BaseService
{
    public function getPersonalInfo(): ?array
    {
        return $this->fetchOne('SELECT * FROM personal_info LIMIT 1');
    }

    public function getTechnicalSkills(): array
    {
        return $this->fetchAll(
            'SELECT * FROM technical_skills ORDER BY display_order ASC'
        );
    }

    public function getTechnicalTools(int $skillId): array
    {
        return $this->fetchAll(
            'SELECT * FROM technical_tools WHERE skill_id = :skill_id ORDER BY proficiency DESC',
            [':skill_id' => $skillId]
        );
    }

    public function getWorkExperience(): array
    {
        return $this->fetchAll(
            'SELECT * FROM work_experience ORDER BY display_order ASC, start_date DESC'
        );
    }

    public function getWorkAchievements(int $workId): array
    {
        return $this->fetchAll(
            'SELECT * FROM work_achievements WHERE work_id = :work_id ORDER BY display_order ASC',
            [':work_id' => $workId]
        );
    }

    public function getCertifications(): array
    {
        return $this->fetchAll(
            'SELECT * FROM certifications ORDER BY display_order ASC, issue_date DESC'
        );
    }

    public function getLanguages(): array
    {
        return $this->fetchAll(
            'SELECT * FROM languages ORDER BY display_order ASC'
        );
    }

    public function getKeyAchievements(): array
    {
        return $this->fetchAll(
            'SELECT * FROM key_achievements WHERE is_active = 1 ORDER BY display_order ASC'
        );
    }

    public function getProfessionalGoals(): array
    {
        return $this->fetchAll(
            'SELECT * FROM professional_goals WHERE is_completed = 0 ORDER BY display_order ASC'
        );
    }

    public function getProfessionalReferences(): array
    {
        return $this->fetchAll(
            'SELECT * FROM professional_references WHERE is_public = 1'
        );
    }

    public function getDiplomas(): array
    {
        return $this->fetchAll(
            'SELECT * FROM diplomas ORDER BY display_order ASC'
        );
    }
}
