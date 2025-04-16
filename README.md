# Документация API

## Общая информация

Это RESTful API для аутентификации и регистрации пользователей с использованием JWT (JSON Web Token). API поддерживает следующие операции:

- Проверка статуса авторизации.
- Вход пользователя (авторизация).
- Регистрация нового пользователя.

API использует CORS (Cross-Origin Resource Sharing) для разрешения кросс-доменных запросов.

---

## 1. Конечные точки (Endpoints)

### 1.1 Проверка статуса авторизации

**Метод:** `GET`
**URL:** `/auth`

#### Описание:
Проверяет, авторизован ли пользователь, анализируя JWT в заголовке `Authorization`.

#### Заголовки:
- `Authorization: Bearer <JWT>` — Токен авторизации.

#### Ответы:
| Код ответа | Описание                                                                                   | Пример ответа                                                                                  |
|------------|-------------------------------------------------------------------------------------------|----------------------------------------------------------------------------------------------|
| 200 OK     | Успешная проверка статуса.                                                                | `{ "status": "authenticated", "user_id": 123 }`                                                |
| 200 OK     | Пользователь не авторизован.                                                              | `{ "status": "not_authenticated" }`                                                           |

---

### 1.2 Авторизация пользователя

**Метод:** `POST`
**URL:** `/auth`

#### Описание:
Осуществляет вход пользователя по логину (email или телефон) и паролю. Возвращает JWT при успешной аутентификации.

#### Тело запроса (JSON):
```json
{
"login": "example@example.com", // Email или телефон
"password": "password123"       // Пароль
}
```

#### Ответы:
| Код ответа | Описание                                                                                   | Пример ответа                                                                                  |
|------------|-------------------------------------------------------------------------------------------|----------------------------------------------------------------------------------------------|
| 200 OK     | Успешный вход.                                                                            | `{ "message": "Login successful", "token": "<JWT>" }`                                          |
| 400 Bad Request | Отсутствуют обязательные параметры.                                                   | `{ "error": "Missing login or password" }`                                                    |
| 401 Unauthorized | Неверные учётные данные.                                                               | `{ "error": "Invalid credentials" }`                                                          |

---

### 1.3 Регистрация нового пользователя

**Метод:** `POST`
**URL:** `/register`

#### Описание:
Регистрирует нового пользователя с указанным логином (email или телефон), паролем, именем и фамилией.

#### Тело запроса (JSON):
```json
{
"login": "example@example.com", // Email или телефон
"password": "password123",      // Пароль
"name": "John",                // Имя (необязательно)
"surname": "Doe"              // Фамилия (необязательно)
}
```

#### Правила валидации:
1. **Логин:** Может быть email или телефоном.
- Если это email, он должен быть корректным.
- Если это телефон, он должен быть в формате `+7XXXXXXXXXX`.
2. **Пароль:** Минимум 6 символов, должен содержать буквы и цифры.
3. **Email или телефон не должны существовать в базе данных.**

#### Ответы:
| Код ответа | Описание                                                                                   | Пример ответа                                                                                  |
|------------|-------------------------------------------------------------------------------------------|----------------------------------------------------------------------------------------------|
| 200 OK     | Успешная регистрация.                                                                     | `{ "message": "User registered", "id": 123 }`                                                 |
| 400 Bad Request | Отсутствуют обязательные параметры.                                                   | `{ "error": "Missing login or password" }`                                                    |
| 400 Bad Request | Слабый пароль.                                                                         | `{ "error": "Weak password. Password must be at least 6 characters long and contain letters and numbers" }` |
| 409 Conflict | Пользователь с указанным email или телефоном уже существует.                              | `{ "error": "User with this email already exists" }`<br>`{ "error": "User with this number already exists" }` |

---

## 2. Примеры использования

### 2.1 Проверка статуса авторизации

**Запрос:**
```http
GET /auth HTTP/1.1
Host: api.nws-official.ru
Authorization: Bearer <JWT>
```

**Ответ:**
```json
{
"status": "authenticated",
"user_id": 123
}
```

---

### 2.2 Авторизация пользователя

**Запрос:**
```http
POST /auth HTTP/1.1
Host: api.nws-official.ru
Content-Type: application/json

{
"login": "example@example.com",
"password": "password123"
}
```

**Ответ:**
```json
{
"message": "Login successful",
"token": "<JWT>"
}
```

---

### 2.3 Регистрация нового пользователя

**Запрос:**
```http
POST /register HTTP/1.1
Host: api.nws-official.ru
Content-Type: application/json

{
"login": "newuser@example.com",
"password": "securePass123",
"name": "New",
"surname": "User"
}
```

**Ответ:**
```json
{
"message": "User registered",
"id": 123
}
```

---

## 3. Дополнительная информация

### 3.1 JWT (JSON Web Token)

- **Структура токена:** `header.payload.signature`
- **Алгоритм подписи:** HMAC SHA256
- **Срок действия:** 1 час (`exp` claim).

---

### 3.2 Нормализация номера телефона

Номер телефона преобразуется в формат `+7XXXXXXXXXX`. Например:
- `89123456789` → `+79123456789`
- `79123456789` → `+79123456789`
- `9123456789` → `+79123456789`

Если номер имеет другой формат, возвращается ошибка.

---

### 3.3 Безопасность пароля

Пароль должен быть минимум 6 символов длиной и содержать как буквы, так и цифры.

---

## 4. Замечания

1. Для хранения паролей рекомендуется использовать более безопасные методы, например, bcrypt вместо MD5.
2. Секретный ключ `$jwt_secret` должен храниться в защищенном месте и не должен быть доступен публично.
3. При необходимости можно добавить дополнительные конечные точки для управления данными пользователей или других ресурсов.

---

Вы можете скопировать этот текст и вставить его в Microsoft Word. Чтобы сделать документ более профессиональным, используйте следующие рекомендации:
- Установите шрифт (например, Arial или Calibri).
- Настройте размер шрифта (например, 11 или 12).
- Добавьте нумерацию разделов.
- Используйте таблицы для структуризации информации.
