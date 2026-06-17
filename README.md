# Wishlist API — Laravel & Symfony

Навчальний проєкт із реалізацією CRUD-операцій для сутності **Wishlist** у двох фреймворках — Laravel та Symfony. Обидва контролери побудовані за зразком `TestController` з оригінального прикладу: дані зберігаються у статичній константі `DATA` (масив у пам'яті), без підключення до бази даних.

---

## 1. Структура проєкту

```
frameworks/
├── laravel/
│   ├── app/Http/Controllers/
│   │   ├── TestController.php          (оригінал, Product)
│   │   └── WishlistController.php      (новий, Wishlist)
│   └── routes/web.php                  (тут прописані всі маршрути)
│
└── symfony/
    └── src/Controller/
        ├── TestController.php          (оригінал, Product)
        └── WishlistController.php      (новий, маршрути прямо в коді через #[Route])
```

Postman-колекції лежать окремо:
- `laravel_wishlist_postman_collection.json`
- `symfony_wishlist_postman_collection.json`

---

## 2. Laravel — встановлення та запуск

### 2.1 Куди класти файли
- `WishlistController.php` → `laravel/app/Http/Controllers/`
- `web.php` → замінити повністю `laravel/routes/web.php` (там вже є й старі Product-маршрути, й нові Wishlist)

### 2.2 Перше налаштування (якщо ще не робили)
У терміналі, з папки `laravel`:

```bash
composer install
copy .env.example .env        # або cp .env.example .env на Linux/Mac
php artisan key:generate
```

### 2.3 Важливо: налаштування сесій
Маршрути в `web.php` проходять через middleware `web`, яке намагається стартувати сесію. За замовчуванням у `.env` стоїть `SESSION_DRIVER=database`, що вимагає реальної БД і таблиці `sessions`. Якщо БД не налаштована — отримаєте `500 Internal Server Error` навіть на найпростіших маршрутах.

**Рішення для цього проєкту** (БД не потрібна, дані статичні):

Відкрийте `laravel/.env`, знайдіть і змініть:
```
SESSION_DRIVER=file
```

Потім очистіть кеш конфігурації:
```bash
php artisan config:clear
```

### 2.4 Запуск сервера
```bash
php artisan serve
```
За замовчуванням сервер піднімається на `http://127.0.0.1:8000` (або `http://localhost:8000`).

---

## 3. Symfony — встановлення та запуск

### 3.1 Куди класти файли
- `WishlistController.php` → `symfony/src/Controller/`

Маршрути в Symfony прописані прямо над методами контролера через атрибути `#[Route(...)]`, тому окремо редагувати файл маршрутизації не потрібно — Symfony знаходить їх автоматично.

### 3.2 Встановлення залежностей
```bash
composer install
```

### 3.3 Запуск сервера
Якщо встановлено Symfony CLI:
```bash
symfony server:start
```
Або через вбудований PHP-сервер:
```bash
php -S localhost:8000 -t public
```

---

## 4. Wishlist API — список endpoint'ів

Однакові маршрути для обох фреймворків (Laravel: `/wishlist`, Symfony: `/wishlist`):

| № | Метод | Маршрут | Статус успіху | Призначення |
|---|-------|---------|----------------|-------------|
| 1 | GET | `/wishlist` | 200 | Отримати весь список бажань |
| 2 | GET | `/wishlist/{id}` | 200 | Отримати один елемент за ID |
| 3 | POST | `/wishlist` | 201 | Додати новий елемент |
| 4 | PATCH | `/wishlist/{id}` | 200 / 404 | Часткове оновлення елемента |
| 5 | DELETE | `/wishlist/{id}` | 204 / 404 | Видалити елемент |

### Поля елемента wishlist
```json
{
  "id": "1",
  "name": "PlayStation 5",
  "description": "Ігрова консоль нового поколінн",
  "price": "500"
}
```

### Важливий нюанс
Дані живуть лише в пам'яті процесу, у константі `DATA`. POST/PATCH/DELETE повертають коректну відповідь, але **не зберігають зміни назавжди** — кожен новий запит до сервера працює з тим самим початковим набором із чотирьох товарів. Це нормальна поведінка для навчального прикладу без БД, так само як і в оригінальному `TestController`.

---

## 5. Тестування в Postman

### 5.1 Імпорт колекції
1. Відкрийте Postman.
2. Натисніть **Import** (зліва вгорі).
3. Виберіть файл `laravel_wishlist_postman_collection.json` (або symfony-версію).
4. Колекція з'явиться у списку зліва з усіма 5 запитами.

### 5.2 Перевірка base_url
Кожна колекція має змінну `base_url` (за замовчуванням `http://localhost:8000`).
Щоб перевірити чи змінити: клікніть на назву колекції → вкладка **Variables** → переконайтесь, що значення відповідає тому, що показав термінал після `php artisan serve` / `symfony server:start`.

### 5.3 Послідовність перевірки
Виконуйте запити по черзі, натискаючи **Send**:

1. **GET /wishlist** — має повернути масив із 4 елементів, статус `200`.
2. **GET /wishlist/1** — повертає один елемент (PlayStation 5), статус `200`.
3. **POST /wishlist** — тіло запиту вже заповнене прикладом JSON, після Send отримаєте новий об'єкт зі статусом `201` і випадковим `id`.
4. **PATCH /wishlist/1** — у тілі лише `price`, статус `200`, у відповіді повернеться оновлений елемент.
5. **DELETE /wishlist/1** — статус `204`, тіло відповіді порожнє.

Для перевірки 404-кейсу можна змінити `id` в URL на неіснуючий, наприклад `/wishlist/999`, у PATCH або DELETE запиті.

### 5.4 Приклади відповідей у колекції
У кожному запиті праворуч є вкладка **Examples** — там збережені приклади як успішних відповідей, так і помилок (404), щоб можна було порівняти реальний результат з очікуваним без повторного запуску сервера.

---

## 6. Типові помилки та рішення

| Помилка | Причина | Рішення |
|---|---|---|
| `500 Internal Server Error`, `Database file ... does not exist` | `SESSION_DRIVER=database` без реальної БД | Змінити на `SESSION_DRIVER=file` в `.env`, виконати `php artisan config:clear` |
| `Connection refused` / запит не доходить у Postman | Сервер не запущено або інший порт | Перевірити термінал, де запущено `artisan serve`, синхронізувати `base_url` у Postman |
| `404 Not Found` на всі запити | Не оновлено `web.php` (Laravel) | Перевірити, що маршрути `/wishlist` додані у `routes/web.php` |
| `Class WishlistController not found` | Файл контролера не в тій папці або не той namespace | Laravel: `app/Http/Controllers/`; Symfony: `src/Controller/` |
| `CSRF token mismatch` на POST/PATCH/DELETE | Маршрут не виключено з CSRF-захисту | Перевірити, що в `web.php` є `->withoutMiddleware([VerifyCsrfToken::class])` |

---

## 7. Якщо потрібна реальна БД (опціонально)

Це не обов'язково для Wishlist-завдання, але якщо плануєте далі працювати з `ProductController`, який вже реально звертається до БД через Eloquent:

```bash
# створити порожній файл SQLite
New-Item database\database.sqlite -ItemType File   # PowerShell
# або
touch database/database.sqlite                      # Linux/Mac

php artisan migrate
```

Після цього можна повернути `SESSION_DRIVER=database`, якщо потрібно.