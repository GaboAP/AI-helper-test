import { useState } from 'react';
import { fetchSuggestions } from './api/client';
import type { SuggestionResponse, Model } from './types';

const models: Model[] = ['gpt-4o-mini', 'gpt-4.1-mini', 'o3-mini'];

export default function App() {
  const [prompt, setPrompt] = useState('');
  const [model, setModel] = useState<Model>('gpt-4o-mini');
  const [data, setData] = useState<SuggestionResponse | null>(null);
  const [loading, setLoading] = useState(false);
  const [err, setErr] = useState<string | null>(null);

  async function onGenerate(e?: React.FormEvent) {
    e?.preventDefault();
    if (!prompt.trim()) return;

    setLoading(true);
    setErr(null);
    setData(null);

    try {
      const json = await fetchSuggestions(prompt.trim(), model);
      setData(json);
    } catch (error: any) {
      setErr(error?.message ?? 'Something went wrong');
    } finally {
      setLoading(false);
    }
  }

  return (
    <div className="min-h-screen flex items-start justify-center p-6 bg-gray-50">
      <div className="w-full max-w-xl bg-white rounded-xl shadow p-5">
        <h1 className="text-2xl font-semibold mb-4">Content Idea Generator</h1>

        <form onSubmit={onGenerate} className="space-y-3">
          <label className="block" id="prompt-container">
            <span id="topic" className="text-sm font-medium">Topic / Prompt</span>
            <input
              value={prompt}
              onChange={(e) => setPrompt(e.target.value)}
              placeholder="e.g., local election, sports news"
              className="mt-1 w-full rounded-md border px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500"
            />
          </label>

          <label className="block">
            <span id="modellabel" className="text-sm font-medium">Model</span>
            <select
              value={model}
              onChange={(e) => setModel(e.target.value as Model)}
              className="mt-1 w-full rounded-md border px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500 bg-white"
            >
              {models.map(m => <option key={m} value={m}>{m}</option>)}
            </select>
          </label>

          <div className="flex gap-2" id="buttons">
            <button
              type="submit"
              disabled={loading || !prompt.trim()}
              className="rounded-md bg-blue-600 text-white px-4 py-2 disabled:opacity-60"
            >
              {loading ? 'Generating…' : 'Generate'}
            </button>
            <button
              type="button"
              onClick={() => { setPrompt(''); setData(null); setErr(null); }}
              className="rounded-md border px-4 py-2"
            >
              Reset
            </button>
          </div>
        </form>

        {err && (
          <div className="mt-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded p-3">
            {err}
          </div>
        )}

        {data && (
          <div className="mt-6">
            <div className="text-sm text-gray-600 mb-2">
              Suggestions for: <span className="font-medium">{data.prompt}</span>
            </div>
            <ul className="list-disc pl-5 space-y-2">
              {data.suggestions.map((s, idx) => (
                <li key={idx} className="leading-relaxed">{s}</li>
              ))}
            </ul>
          </div>
        )}
      </div>
    </div>
  );
}