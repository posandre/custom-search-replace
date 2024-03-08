# Custom search replace plugin

# **Розробка плагіна для WordPress**

**Мета завдання:**

Розробити плагін для WordPress, який додає в адміністративну панель окремий пункт з формою для пошуку постів за ключовим словом у заголовку, вмісті, метазаголовку та метаописі.

- **Опис завдання**
    - Створити WordPress плагін.
    - Плагін має додавати окремий пункт в адміністративну панель WordPress.
    - На сторінці цього пункту повинна бути розміщена форма пошуку (input + submit button).
    - Пошук має виконуватися за певним словом, введеним у форму, і виводити таблицю з постами, де це слово зустрічається у:
        - Заголовку (Title)
        - Вмісті (Content)
        - Метаз-аголовку (Meta-title)
        - Мета-описі (Meta-description)
- **Функціональні вимоги**
    - У кожному стовпчику таблиці (вгорі) додати форму (input + submit button) для заміни слова.
    - При введенні нового слова в інпут і натисканні на submit button має відбуватися заміна старого слова на нове для всіх постів.
    - Форми повинні працювати на Ajax без перезавантаження сторінки.
- **Технологічні вимоги**
    - Використовувати PHP (ООП), JS (JQuery), Ajax, WP hooks (action, filters).
    - Для формування Meta-title і Meta-description використовувати плагін Yoast SEO.
 - **Зразок макета**

   <img width="600" style="display: block;" alt="image" src="https://github.com/posandre/custom-search-replace/assets/45790427/5fef2376-986b-49c2-84ce-4703922b013f">
