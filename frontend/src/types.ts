export type SuggestionResponse = {
  prompt: string;
  suggestions: string[];
};

export type Model = 'gpt-4o-mini' | 'gpt-4.1-mini' | 'o3-mini';