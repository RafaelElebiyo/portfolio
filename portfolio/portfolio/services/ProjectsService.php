<?php
declare(strict_types=1);

require_once __DIR__ . '/BaseService.php';

class ProjectsService extends BaseService
{
    private const VALID_ORDER = ['display_order', 'project_date', 'popularity'];

    public function getAllProjects(string $orderBy = 'display_order'): array
    {
        // Whitelist order column — never interpolate user input directly
        $col = in_array($orderBy, self::VALID_ORDER, true) ? $orderBy : 'display_order';

        $sql = "SELECT p.*,
                GROUP_CONCAT(DISTINCT f.feature        ORDER BY f.display_order  SEPARATOR '|||') AS features,
                GROUP_CONCAT(DISTINCT t.technology     ORDER BY t.display_order  SEPARATOR ',')   AS technologies,
                GROUP_CONCAT(DISTINCT CONCAT(cs.language,':::',cs.code)
                             ORDER BY cs.display_order SEPARATOR '|||')                           AS code_samples
                FROM projects p
                LEFT JOIN project_features      f  ON p.id = f.project_id
                LEFT JOIN project_technologies  t  ON p.id = t.project_id
                LEFT JOIN code_samples          cs ON p.id = cs.project_id
                GROUP BY p.id
                ORDER BY {$col} ASC";

        return $this->fetchAll($sql);
    }

    public function getFeaturedProjects(): array
    {
        return $this->fetchAll(
            'SELECT * FROM projects WHERE is_featured = 1 ORDER BY display_order ASC, popularity DESC'
        );
    }

    public function getProjectById(int $id): ?array
    {
        return $this->fetchOne(
            'SELECT * FROM projects WHERE id = :id LIMIT 1',
            [':id' => $id]
        );
    }

    public function getProjectFeatures(int $projectId): array
    {
        return $this->fetchAll(
            'SELECT * FROM project_features WHERE project_id = :id ORDER BY display_order ASC',
            [':id' => $projectId]
        );
    }
}
