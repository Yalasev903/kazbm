const CACHE_NAME = 'kazbm-v3';
const urlsToCache = [
  '/',
  '/css/style.css',
  '/css/dep.min.css',
  '/js/script.js'
];

self.addEventListener('install', function(event) {
  event.waitUntil(
    caches.open(CACHE_NAME)
      .then(function(cache) {
        console.log('Cache opened');
        return cache.addAll(urlsToCache);
      })
  );
});

self.addEventListener('fetch', function(event) {
  // 🔴 ВАЖНО: НЕ перехватываем AJAX запросы, пагинацию и динамические данные
  const url = new URL(event.request.url);

  if (event.request.url.includes('/ajax/') ||
      event.request.url.includes('?page=') ||
      event.request.url.includes('/filter/') ||
      event.request.method !== 'GET' ||
      url.searchParams.has('page')) {
    // Пропускаем эти запросы - они должны идти напрямую к серверу
    return;
  }

  event.respondWith(
    caches.match(event.request)
      .then(function(response) {
        // Возвращаем кэш или делаем сетевой запрос
        if (response) {
          return response;
        }
        return fetch(event.request);
      })
  );
});
