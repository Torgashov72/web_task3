<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Форма заявки</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 40px 20px;
        }
        .container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #34495e;
            font-weight: 600;
        }
        input[type="text"],
        input[type="tel"],
        input[type="email"],
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
        }
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #667eea;
        }
        textarea {
            min-height: 120px;
            resize: vertical;
        }
        .radio-group {
            display: flex;
            gap: 20px;
        }
        .radio-group label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-weight: normal;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .checkbox-group label {
            font-weight: normal;
        }
        select[multiple] {
            min-height: 150px;
        }
        button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 18px;
            cursor: pointer;
        }
        button:hover {
            opacity: 0.9;
        }
        .error {
            background: #fee;
            border: 1px solid #fcc;
            color: #c00;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .success {
            background: #efe;
            border: 1px solid #cfc;
            color: #060;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .required { color: #e74c3c; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Форма заявки</h1>
        
        <?php if (!empty($errors)): ?>
        <div class="error">
            <strong>Ошибка!</strong><br/>
            <?php foreach ($errors as $error): ?>
                • <?php echo htmlspecialchars($error); ?><br/>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['save']) && $_GET['save'] == 1): ?>
        <div class="success">
            <strong>Успешно!</strong> Данные сохранены в базу данных.
        </div>
        <?php endif; ?>
        
        <form action="index.php" method="POST">
            <!-- 1. ФИО -->
            <div class="form-group">
                <label>ФИО <span class="required">*</span></label>
                <input type="text" name="fio" value="<?php echo htmlspecialchars($_POST['fio'] ?? ''); ?>" required/>
            </div>
            
            <!-- 2. Телефон -->
            <div class="form-group">
                <label>Телефон <span class="required">*</span></label>
                <input type="tel" name="phone" value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>" required/>
            </div>
            
            <!-- 3. E-mail -->
            <div class="form-group">
                <label>E-mail <span class="required">*</span></label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required/>
            </div>
            
            <!-- 4. Дата рождения -->
            <div class="form-group">
                <label>Дата рождения <span class="required">*</span></label>
                <input type="date" name="birthdate" value="<?php echo htmlspecialchars($_POST['birthdate'] ?? ''); ?>" required/>
            </div>
            
            <!-- 5. Пол -->
            <div class="form-group">
                <label>Пол <span class="required">*</span></label>
                <div class="radio-group">
                    <label>
                        <input type="radio" name="gender" value="male" <?php echo (($_POST['gender'] ?? '') == 'male') ? 'checked' : ''; ?> required/> Мужской
                    </label>
                    <label>
                        <input type="radio" name="gender" value="female" <?php echo (($_POST['gender'] ?? '') == 'female') ? 'checked' : ''; ?>/> Женский
                    </label>
                </div>
            </div>
            
            <!-- 6. Языки программирования -->
            <div class="form-group">
                <label>Любимые языки программирования <span class="required">*</span></label>
                <select name="languages[]" multiple="multiple" required>
                    <option value="1">Pascal</option>
                    <option value="2">C</option>
                    <option value="3">C++</option>
                    <option value="4">JavaScript</option>
                    <option value="5">PHP</option>
                    <option value="6">Python</option>
                    <option value="7">Java</option>
                    <option value="8">Haskell</option>
                    <option value="9">Clojure</option>
                    <option value="10">Prolog</option>
                    <option value="11">Scala</option>
                    <option value="12">Go</option>
                </select>
                <small>Зажмите Ctrl для выбора нескольких языков</small>
            </div>
            
            <!-- 7. Биография -->
            <div class="form-group">
                <label>Биография <span class="required">*</span></label>
                <textarea name="biography" required><?php echo htmlspecialchars($_POST['biography'] ?? ''); ?></textarea>
            </div>
            
            <!-- 8. Чекбокс -->
            <div class="form-group">
                <div class="checkbox-group">
                    <input type="checkbox" name="contract" value="1" <?php echo !empty($_POST['contract']) ? 'checked' : ''; ?> required/>
                    <label>С контрактом ознакомлен(а) <span class="required">*</span></label>
                </div>
            </div>
            
            <!-- 9. Кнопка -->
            <button type="submit" name="submit">Сохранить</button>
        </form>
    </div>
</body>
</html>
