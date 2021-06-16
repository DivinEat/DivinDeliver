const url = new URL("http://localhost:9090/.well-known/mercure");
url.searchParams.append("topic", "http://localhost.8082/menus/");

const eventSource = new EventSource(url);
eventSource.onmessage = (event) => {
  console.log(JSON.parse(event.data));
};
