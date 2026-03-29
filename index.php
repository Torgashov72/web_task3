<?php
header('Content-Type: text/html; charset=UTF-8');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ========== ВАЛИДАЦИЯ ==========
    
    // 1. ФИО (только буквы и пробелы, до 150 символов)
    if (empty($_POST['fio'])) {
        $errors[] = 'Заполните поле ФИО';
    } elseif (!preg_match('/^[а-яА-ЯёЁa-zA-Z\s]{2,150}$/u', $_POST['fio'])) {
        $errors[] = 'ФИО должно содержать только буквы и пробелы (2-150 символов)';
    }
    
    // 2. Телефон
    if (empty($_POST['phone'])) {
        $errors[] = 'Заполните поле Телефон';
    } elseif (!preg_match('/^[\d\+\-\(\)\s]{10,20}$/', $_POST['phone'])) {
        $errors[] = 'Некорректный формат телефона';
    }
    
    // 3. E-mail
    if (empty($_POST['email'])) {
        $errors[] = 'Заполните поле E-mail';
    } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Некорректный формат E-mail';
    }
    
    // 4. Дата рождения
    if (empty($_POST['birthdate'])) {
        $errors[] = 'Заполните поле Дата рождения';
    } else {
        $date = DateTime::createFromFormat('Y-m-d', $_POST['birthdate']);
        if (!$date || $date->format('Y-m-d') !== $_POST['birthdate']) {
            $errors[] = 'Некорректный формат даты';
        }
    }
    
    // 5. Пол
    if (empty($_POST['gender']) || !in_array($_POST['gender'], ['male', 'female'])) {
        $errors[] = 'Выберите пол';
    }
    
    // 6. Языки программирования
    if (empty($_POST['languages']) || !is_array($_POST['languages'])) {
        $errors[] = 'Выберите хотя бы один язык программирования';
    } else {
        foreach ($_POST['languages'] as $lang_id) {
            if (!is_numeric($lang_id) || $lang_id < 1 || $lang_id > 12) {
                $errors[] = 'Некорректный выбор языка программирования';
                break;
            }
        }
    }
    
    // 7. Биография
    if (empty($_POST['biography'])) {
        $errors[] = 'Заполните поле Биография';
    } elseif (strlen($_POST['biography']) < 10) {
        $errors[] = 'Биография должна быть не менее 10 символов';
    }
    
    // 8. Чекбокс
    if (empty($_POST['contract'])) {
        $errors[] = 'Вы должны ознакомиться с контрактом';
    }
    
    // ========== СОХРАНЕНИЕ В БД ==========
    
    if (empty($errors)) {
        try {
            $db = new PDO(
                'mysql:host=localhost;dbname=u82364;charset=utf8mb4',
                'u82364',
                '2807705',
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]
            );
            
            // Вставка в applications
            $stmt = $db->prepare("
                INSERT INTO applications (fio, phone, email, birthdate, gender, biography, contract_agreed)
                VALUES (:fio, :phone, :email, :birthdate, :gender, :biography, :contract)
            ");
            
            $stmt->execute([
                ':fio' => $_POST['fio'],
                ':phone' => $_POST['phone'],
                ':email' => $_POST['email'],
                ':birthdate' => $_POST['birthdate'],
                ':gender' => $_POST['gender'],
                ':biography' => $_POST['biography'],
                ':contract' => !empty($_POST['contract']) ? 1 : 0
            ]);
            
            $application_id = $db->lastInsertId();
            
            // Вставка в application_languages
            $stmt_lang = $db->prepare("
                INSERT INTO application_languages (application_id, language_id)
                VALUES (:app_id, :lang_id)
            ");
            
            foreach ($_POST['languages'] as $lang_id) {
                $stmt_lang->execute([
                    ':app_id' => $application_id,
                    ':lang_id' => $lang_id
                ]);
            }
            
            header('Location: ?save=1');
            exit();
            
        } catch (PDOException $e) {
            $errors[] = 'Ошибка базы данных: ' . $e->getMessage();
        }
    }
}

// Показываем форму
include('form.php');
?>
