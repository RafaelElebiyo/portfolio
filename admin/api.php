<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/bootstrap.php';
require_once __DIR__ . '/../middleware/AdminAuthMiddleware.php';
require_once __DIR__ . '/../helpers/CsrfToken.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Invalid request'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if (!AdminAuthMiddleware::isAuthenticated()) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized']);
        exit;
    }

    $csrfToken = $_POST['csrf_token'] ?? '';
    if (!CsrfToken::validate($csrfToken)) {
        echo json_encode(['success' => false, 'message' => 'Invalid CSRF token']);
        exit;
    }

    $user = AdminAuthMiddleware::getCurrentUser();
    $adminService = new AdminService();
    $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';

    switch ($action) {
        // Projects CRUD
        case 'create_project':
            $title = Sanitizer::string($_POST['title'] ?? '');
            $description = Sanitizer::string($_POST['description'] ?? '');
            $category = Sanitizer::string($_POST['category'] ?? 'web');
            $tech_stack = Sanitizer::string($_POST['tech_stack'] ?? '');
            $image_url = Sanitizer::string($_POST['image_url'] ?? '');
            $live_url = Sanitizer::string($_POST['live_url'] ?? '');
            $github_url = Sanitizer::string($_POST['github_url'] ?? '');
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;

            if (empty($title)) {
                $response = ['success' => false, 'message' => 'Title is required'];
            } else {
                $slug = strtolower(preg_replace('/[^a-zA-Z0-9-]/', '-', trim($title)));
                $sql = 'INSERT INTO projects (title, slug, short_description, full_description, category, cover_image, project_url, github_url, is_featured) 
                        VALUES (:title, :slug, :short_desc, :full_desc, :cat, :cover, :url, :git, :featured)';
                $id = (new ProjectsService())->insert($sql, [
                    ':title' => $title, ':slug' => $slug, ':short_desc' => $description, ':full_desc' => $description,
                    ':cat' => $category, ':cover' => $image_url, ':url' => $live_url,
                    ':git' => $github_url, ':featured' => $is_featured
                ]);
                
                if ($id) {
                    $adminService->logAction($user['id'], 'CREATE', 'projects', $id, null, json_encode($_POST), $ipAddress);
                    $response = ['success' => true, 'message' => 'Project created', 'id' => $id];
                } else {
                    $response = ['success' => false, 'message' => 'Failed to create project'];
                }
            }
            break;

        case 'update_project':
            $id = (int)($_POST['id'] ?? 0);
            $title = Sanitizer::string($_POST['title'] ?? '');
            $description = Sanitizer::string($_POST['description'] ?? '');
            $category = Sanitizer::string($_POST['category'] ?? 'web');
            $tech_stack = Sanitizer::string($_POST['tech_stack'] ?? '');
            $image_url = Sanitizer::string($_POST['image_url'] ?? '');
            $live_url = Sanitizer::string($_POST['live_url'] ?? '');
            $github_url = Sanitizer::string($_POST['github_url'] ?? '');
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;

            if (empty($id) || empty($title)) {
                $response = ['success' => false, 'message' => 'ID and title are required'];
            } else {
                $oldData = (new ProjectsService())->getProjectById($id);
                
                $sql = 'UPDATE projects SET title=:title, short_description=:short_desc, full_description=:full_desc, category=:cat, cover_image=:cover, project_url=:url, github_url=:git, is_featured=:featured WHERE id=:id';
                $result = (new ProjectsService())->execute($sql, [
                    ':id' => $id, ':title' => $title, ':short_desc' => $description, ':full_desc' => $description,
                    ':cat' => $category, ':cover' => $image_url, ':url' => $live_url,
                    ':git' => $github_url, ':featured' => $is_featured
                ]);

                if ($result) {
                    $adminService->logAction($user['id'], 'UPDATE', 'projects', $id, json_encode($oldData), json_encode($_POST), $ipAddress);
                    $response = ['success' => true, 'message' => 'Project updated'];
                } else {
                    $response = ['success' => false, 'message' => 'Failed to update project'];
                }
            }
            break;

        case 'delete_project':
            $id = (int)($_POST['id'] ?? 0);
            
            if (empty($id)) {
                $response = ['success' => false, 'message' => 'ID is required'];
            } else {
                $oldData = (new ProjectsService())->getProjectById($id);
                $result = (new ProjectsService())->execute('DELETE FROM projects WHERE id = :id', [':id' => $id]);
                
                if ($result) {
                    $adminService->logAction($user['id'], 'DELETE', 'projects', $id, json_encode($oldData), null, $ipAddress);
                    $response = ['success' => true, 'message' => 'Project deleted'];
                } else {
                    $response = ['success' => false, 'message' => 'Failed to delete project'];
                }
            }
            break;

        // Skills CRUD
        case 'create_skill':
            $name = Sanitizer::string($_POST['name'] ?? '');
            $category = Sanitizer::string($_POST['category'] ?? 'other');
            $proficiency = (int)($_POST['proficiency'] ?? 80);
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;

            if (empty($name)) {
                $response = ['success' => false, 'message' => 'Name is required'];
            } else {
                $id = (new ResumeService())->insert(
                    'INSERT INTO technical_skills (name, category, proficiency, is_featured, display_order) VALUES (:name, :cat, :prof, :featured, (SELECT COALESCE(MAX(display_order),0)+1 FROM (SELECT display_order FROM technical_skills) t))',
                    [':name' => $name, ':cat' => $category, ':prof' => $proficiency, ':featured' => $is_featured]
                );
                
                if ($id) {
                    $adminService->logAction($user['id'], 'CREATE', 'technical_skills', $id, null, json_encode($_POST), $ipAddress);
                    $response = ['success' => true, 'message' => 'Skill created', 'id' => $id];
                } else {
                    $response = ['success' => false, 'message' => 'Failed to create skill'];
                }
            }
            break;

        case 'update_skill':
            $id = (int)($_POST['id'] ?? 0);
            $name = Sanitizer::string($_POST['name'] ?? '');
            $category = Sanitizer::string($_POST['category'] ?? 'other');
            $proficiency = (int)($_POST['proficiency'] ?? 80);
            $is_featured = isset($_POST['is_featured']) ? 1 : 0;

            if (empty($id) || empty($name)) {
                $response = ['success' => false, 'message' => 'ID and name are required'];
            } else {
                $result = (new ResumeService())->execute(
                    'UPDATE technical_skills SET name=:name, category=:cat, proficiency=:prof, is_featured=:featured WHERE id=:id',
                    [':id' => $id, ':name' => $name, ':cat' => $category, ':prof' => $proficiency, ':featured' => $is_featured]
                );

                if ($result) {
                    $adminService->logAction($user['id'], 'UPDATE', 'technical_skills', $id, null, json_encode($_POST), $ipAddress);
                    $response = ['success' => true, 'message' => 'Skill updated'];
                } else {
                    $response = ['success' => false, 'message' => 'Failed to update skill'];
                }
            }
            break;

        case 'delete_skill':
            $id = (int)($_POST['id'] ?? 0);
            
            if (empty($id)) {
                $response = ['success' => false, 'message' => 'ID is required'];
            } else {
                $result = (new ResumeService())->execute('DELETE FROM technical_skills WHERE id = :id', [':id' => $id]);
                
                if ($result) {
                    $adminService->logAction($user['id'], 'DELETE', 'technical_skills', $id, null, null, $ipAddress);
                    $response = ['success' => true, 'message' => 'Skill deleted'];
                } else {
                    $response = ['success' => false, 'message' => 'Failed to delete skill'];
                }
            }
            break;

        // Certifications CRUD
        case 'create_certification':
            $name = Sanitizer::string($_POST['name'] ?? '');
            $issuer = Sanitizer::string($_POST['issuer'] ?? '');
            $issue_date = Sanitizer::string($_POST['issue_date'] ?? '');
            $expiry_date = Sanitizer::string($_POST['expiry_date'] ?? '');
            $credential_id = Sanitizer::string($_POST['credential_id'] ?? '');
            $credential_url = Sanitizer::string($_POST['credential_url'] ?? '');

            if (empty($name)) {
                $response = ['success' => false, 'message' => 'Name is required'];
            } else {
                $id = (new ResumeService())->insert(
                    'INSERT INTO certifications (name, issuer, issue_date, expiry_date, credential_id, credential_url) VALUES (:name, :issuer, :issue, :expiry, :cred_id, :cred_url)',
                    [':name' => $name, ':issuer' => $issuer, ':issue' => $issue_date, ':expiry' => $expiry_date, ':cred_id' => $credential_id, ':cred_url' => $credential_url]
                );
                
                if ($id) {
                    $adminService->logAction($user['id'], 'CREATE', 'certifications', $id, null, json_encode($_POST), $ipAddress);
                    $response = ['success' => true, 'message' => 'Certification created', 'id' => $id];
                } else {
                    $response = ['success' => false, 'message' => 'Failed to create certification'];
                }
            }
            break;

        case 'update_certification':
            $id = (int)($_POST['id'] ?? 0);
            $name = Sanitizer::string($_POST['name'] ?? '');
            $issuer = Sanitizer::string($_POST['issuer'] ?? '');
            $issue_date = Sanitizer::string($_POST['issue_date'] ?? '');
            $expiry_date = Sanitizer::string($_POST['expiry_date'] ?? '');
            $credential_id = Sanitizer::string($_POST['credential_id'] ?? '');
            $credential_url = Sanitizer::string($_POST['credential_url'] ?? '');

            if (empty($id) || empty($name)) {
                $response = ['success' => false, 'message' => 'ID and name are required'];
            } else {
                $result = (new ResumeService())->execute(
                    'UPDATE certifications SET name=:name, issuer=:issuer, issue_date=:issue, expiry_date=:expiry, credential_id=:cred_id, credential_url=:cred_url WHERE id=:id',
                    [':id' => $id, ':name' => $name, ':issuer' => $issuer, ':issue' => $issue_date, ':expiry' => $expiry_date, ':cred_id' => $credential_id, ':cred_url' => $credential_url]
                );

                if ($result) {
                    $adminService->logAction($user['id'], 'UPDATE', 'certifications', $id, null, json_encode($_POST), $ipAddress);
                    $response = ['success' => true, 'message' => 'Certification updated'];
                } else {
                    $response = ['success' => false, 'message' => 'Failed to update certification'];
                }
            }
            break;

        case 'delete_certification':
            $id = (int)($_POST['id'] ?? 0);
            
            if (empty($id)) {
                $response = ['success' => false, 'message' => 'ID is required'];
            } else {
                $result = (new ResumeService())->execute('DELETE FROM certifications WHERE id = :id', [':id' => $id]);
                
                if ($result) {
                    $adminService->logAction($user['id'], 'DELETE', 'certifications', $id, null, null, $ipAddress);
                    $response = ['success' => true, 'message' => 'Certification deleted'];
                } else {
                    $response = ['success' => false, 'message' => 'Failed to delete certification'];
                }
            }
            break;

        // Diplomas CRUD
        case 'create_diploma':
            $degree = Sanitizer::string($_POST['degree'] ?? '');
            $institution = Sanitizer::string($_POST['institution'] ?? '');
            $start_date = Sanitizer::string($_POST['start_date'] ?? '');
            $end_date = Sanitizer::string($_POST['end_date'] ?? '');
            $description = Sanitizer::string($_POST['description'] ?? '');

            if (empty($degree)) {
                $response = ['success' => false, 'message' => 'Degree is required'];
            } else {
                $id = (new ResumeService())->insert(
                    'INSERT INTO diplomas (degree, institution, start_date, end_date, description) VALUES (:degree, :inst, :start, :end, :desc)',
                    [':degree' => $degree, ':inst' => $institution, ':start' => $start_date, ':end' => $end_date, ':desc' => $description]
                );
                
                if ($id) {
                    $adminService->logAction($user['id'], 'CREATE', 'diplomas', $id, null, json_encode($_POST), $ipAddress);
                    $response = ['success' => true, 'message' => 'Diploma created', 'id' => $id];
                } else {
                    $response = ['success' => false, 'message' => 'Failed to create diploma'];
                }
            }
            break;

        case 'update_diploma':
            $id = (int)($_POST['id'] ?? 0);
            $degree = Sanitizer::string($_POST['degree'] ?? '');
            $institution = Sanitizer::string($_POST['institution'] ?? '');
            $start_date = Sanitizer::string($_POST['start_date'] ?? '');
            $end_date = Sanitizer::string($_POST['end_date'] ?? '');
            $description = Sanitizer::string($_POST['description'] ?? '');

            if (empty($id) || empty($degree)) {
                $response = ['success' => false, 'message' => 'ID and degree are required'];
            } else {
                $result = (new ResumeService())->execute(
                    'UPDATE diplomas SET degree=:degree, institution=:inst, start_date=:start, end_date=:end, description=:desc WHERE id=:id',
                    [':id' => $id, ':degree' => $degree, ':inst' => $institution, ':start' => $start_date, ':end' => $end_date, ':desc' => $description]
                );

                if ($result) {
                    $adminService->logAction($user['id'], 'UPDATE', 'diplomas', $id, null, json_encode($_POST), $ipAddress);
                    $response = ['success' => true, 'message' => 'Diploma updated'];
                } else {
                    $response = ['success' => false, 'message' => 'Failed to update diploma'];
                }
            }
            break;

        case 'delete_diploma':
            $id = (int)($_POST['id'] ?? 0);
            
            if (empty($id)) {
                $response = ['success' => false, 'message' => 'ID is required'];
            } else {
                $result = (new ResumeService())->execute('DELETE FROM diplomas WHERE id = :id', [':id' => $id]);
                
                if ($result) {
                    $adminService->logAction($user['id'], 'DELETE', 'diplomas', $id, null, null, $ipAddress);
                    $response = ['success' => true, 'message' => 'Diploma deleted'];
                } else {
                    $response = ['success' => false, 'message' => 'Failed to delete diploma'];
                }
            }
            break;

        // Languages CRUD
        case 'create_language':
            $language = Sanitizer::string($_POST['language'] ?? '');
            $proficiency = Sanitizer::string($_POST['proficiency'] ?? 'intermediate');

            if (empty($language)) {
                $response = ['success' => false, 'message' => 'Language is required'];
            } else {
                $id = (new ResumeService())->insert(
                    'INSERT INTO languages (language, proficiency) VALUES (:lang, :prof)',
                    [':lang' => $language, ':prof' => $proficiency]
                );
                
                if ($id) {
                    $adminService->logAction($user['id'], 'CREATE', 'languages', $id, null, json_encode($_POST), $ipAddress);
                    $response = ['success' => true, 'message' => 'Language created', 'id' => $id];
                } else {
                    $response = ['success' => false, 'message' => 'Failed to create language'];
                }
            }
            break;

        case 'update_language':
            $id = (int)($_POST['id'] ?? 0);
            $language = Sanitizer::string($_POST['language'] ?? '');
            $proficiency = Sanitizer::string($_POST['proficiency'] ?? 'intermediate');

            if (empty($id) || empty($language)) {
                $response = ['success' => false, 'message' => 'ID and language are required'];
            } else {
                $result = (new ResumeService())->execute(
                    'UPDATE languages SET language=:lang, proficiency=:prof WHERE id=:id',
                    [':id' => $id, ':lang' => $language, ':prof' => $proficiency]
                );

                if ($result) {
                    $adminService->logAction($user['id'], 'UPDATE', 'languages', $id, null, json_encode($_POST), $ipAddress);
                    $response = ['success' => true, 'message' => 'Language updated'];
                } else {
                    $response = ['success' => false, 'message' => 'Failed to update language'];
                }
            }
            break;

        case 'delete_language':
            $id = (int)($_POST['id'] ?? 0);
            
            if (empty($id)) {
                $response = ['success' => false, 'message' => 'ID is required'];
            } else {
                $result = (new ResumeService())->execute('DELETE FROM languages WHERE id = :id', [':id' => $id]);
                
                if ($result) {
                    $adminService->logAction($user['id'], 'DELETE', 'languages', $id, null, null, $ipAddress);
                    $response = ['success' => true, 'message' => 'Language deleted'];
                } else {
                    $response = ['success' => false, 'message' => 'Failed to delete language'];
                }
            }
            break;

        // Work Experience CRUD
        case 'create_experience':
            $position = Sanitizer::string($_POST['position'] ?? '');
            $company = Sanitizer::string($_POST['company'] ?? '');
            $location = Sanitizer::string($_POST['location'] ?? '');
            $employment_type = Sanitizer::string($_POST['employment_type'] ?? 'full-time');
            $start_date = Sanitizer::string($_POST['start_date'] ?? '');
            $end_date = Sanitizer::string($_POST['end_date'] ?? '');
            $is_current = isset($_POST['is_current']) ? 1 : 0;
            $description = Sanitizer::string($_POST['description'] ?? '');

            if (empty($position) || empty($company)) {
                $response = ['success' => false, 'message' => 'Position and company are required'];
            } else {
                $id = (new ResumeService())->insert(
                    'INSERT INTO work_experience (position, company, location, employment_type, start_date, end_date, is_current, description) VALUES (:pos, :comp, :loc, :type, :start, :end, :current, :desc)',
                    [':pos' => $position, ':comp' => $company, ':loc' => $location, ':type' => $employment_type, ':start' => $start_date, ':end' => $end_date, ':current' => $is_current, ':desc' => $description]
                );
                
                if ($id) {
                    $adminService->logAction($user['id'], 'CREATE', 'work_experience', $id, null, json_encode($_POST), $ipAddress);
                    $response = ['success' => true, 'message' => 'Experience created', 'id' => $id];
                } else {
                    $response = ['success' => false, 'message' => 'Failed to create experience'];
                }
            }
            break;

        case 'update_experience':
            $id = (int)($_POST['id'] ?? 0);
            $position = Sanitizer::string($_POST['position'] ?? '');
            $company = Sanitizer::string($_POST['company'] ?? '');
            $location = Sanitizer::string($_POST['location'] ?? '');
            $employment_type = Sanitizer::string($_POST['employment_type'] ?? 'full-time');
            $start_date = Sanitizer::string($_POST['start_date'] ?? '');
            $end_date = Sanitizer::string($_POST['end_date'] ?? '');
            $is_current = isset($_POST['is_current']) ? 1 : 0;
            $description = Sanitizer::string($_POST['description'] ?? '');

            if (empty($id) || empty($position)) {
                $response = ['success' => false, 'message' => 'ID and position are required'];
            } else {
                $result = (new ResumeService())->execute(
                    'UPDATE work_experience SET position=:pos, company=:comp, location=:loc, employment_type=:type, start_date=:start, end_date=:end, is_current=:current, description=:desc WHERE id=:id',
                    [':id' => $id, ':pos' => $position, ':comp' => $company, ':loc' => $location, ':type' => $employment_type, ':start' => $start_date, ':end' => $end_date, ':current' => $is_current, ':desc' => $description]
                );

                if ($result) {
                    $adminService->logAction($user['id'], 'UPDATE', 'work_experience', $id, null, json_encode($_POST), $ipAddress);
                    $response = ['success' => true, 'message' => 'Experience updated'];
                } else {
                    $response = ['success' => false, 'message' => 'Failed to update experience'];
                }
            }
            break;

        case 'delete_experience':
            $id = (int)($_POST['id'] ?? 0);
            
            if (empty($id)) {
                $response = ['success' => false, 'message' => 'ID is required'];
            } else {
                $result = (new ResumeService())->execute('DELETE FROM work_experience WHERE id = :id', [':id' => $id]);
                
                if ($result) {
                    $adminService->logAction($user['id'], 'DELETE', 'work_experience', $id, null, null, $ipAddress);
                    $response = ['success' => true, 'message' => 'Experience deleted'];
                } else {
                    $response = ['success' => false, 'message' => 'Failed to delete experience'];
                }
            }
            break;

        // Personal Info
        case 'update_personal_info':
            $full_name = Sanitizer::string($_POST['full_name'] ?? '');
            $job_title = Sanitizer::string($_POST['job_title'] ?? '');
            $email = Sanitizer::email($_POST['email'] ?? '');
            $phone = Sanitizer::string($_POST['phone'] ?? '');
            $location = Sanitizer::string($_POST['location'] ?? '');
            $short_bio = Sanitizer::string($_POST['short_bio'] ?? '');
            $availability_status = Sanitizer::string($_POST['availability_status'] ?? 'open');

            if (empty($full_name) || empty($email)) {
                $response = ['success' => false, 'message' => 'Name and email are required'];
            } else {
                $result = (new ResumeService())->execute(
                    'UPDATE personal_info SET full_name=:name, job_title=:title, email=:email, phone=:phone, location=:loc, short_bio=:bio, availability_status=:status WHERE id=1',
                    [':name' => $full_name, ':title' => $job_title, ':email' => $email, ':phone' => $phone, ':loc' => $location, ':bio' => $short_bio, ':status' => $availability_status]
                );

                if ($result) {
                    $adminService->logAction($user['id'], 'UPDATE', 'personal_info', 1, null, json_encode($_POST), $ipAddress);
                    $response = ['success' => true, 'message' => 'Personal info updated'];
                } else {
                    $response = ['success' => false, 'message' => 'Failed to update personal info'];
                }
            }
            break;

        default:
            $response = ['success' => false, 'message' => 'Unknown action'];
    }
}

echo json_encode($response);