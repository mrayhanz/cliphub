# Brand Rules — Models & Validation

## Model Expectations

Before adding Brand code, verify model relationships exist/current:

- `User::campaigns()`
- `User::deposits()`
- `Campaign::user()`
- `Campaign::submissions()` if submissions are needed
- `Deposit::user()`

If missing, add relationships in the related model before controller logic.

## Campaign Validation

Campaign creation validation should match current fields:

```php
'title' => 'required|string|max:255',
'type' => 'required|string|in:video,clip',
'slots' => 'required|integer|min:1',
'thumbnail' => 'required|image|mimes:jpeg,png,jpg|max:5120',
'desc' => 'required|string',
'full_brief' => 'required|string',
'donts' => 'required|string',
'assets_url' => 'nullable|url',
'deadline' => 'required|date',
'video_length' => 'required|string|max:50',
'link' => 'required|url',
'platform' => 'required|string',
'budget' => 'required|numeric|min:0',
'price_per_1k' => 'required|numeric|min:0',
```

## Finance Validation

Top-up minimum currently:

```php
'amount' => 'required|numeric|min:10000'
```
