const BASE_URL = import.meta.env.VITE_API_BASE_URL as string;

export async function fetchSuggestions(prompt: string, model: string) {
  const res = await fetch(`${BASE_URL}/api/suggestions`, {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({ prompt, model }),
  });

  if (!res.ok) {
    const text = await res.text().catch(() => '');
    throw new Error(text || `Request failed with ${res.status}`);
  }

  return res.json();
}
