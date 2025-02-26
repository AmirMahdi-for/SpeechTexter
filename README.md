# SpeeechTexter Laravel Package

SpeeechTexter is a Laravel package that simplifies speech-to-text processing by integrating with external services. This package provides an easy way to store, manage, and retrieve transcribed audio files while allowing full flexibility in configuring dependencies such as user and file models.

## 🚀 Features
- Seamless speech-to-text integration
- Configurable API endpoints
- Dynamic model binding for User and File
- Supports Laravel migrations and service providers
- Queued job processing for speech recognition requests
- Storage support for temporary and persistent audio files
- Validation for user inputs and API requests

## 📞 External Service
This package integrates with the **ussistant** speech recognition service for Persian language support. If you intend to use this package for Persian speech-to-text conversion, please refer to the API documentation and services provided by [ussistant](https://ussistant.ir/).

## 📦 Installation
You can install this package via Composer:

```bash
composer require caraxes/speeech-texter
```

After installation, publish the package configuration:

```bash
php artisan vendor:publish --tag=speeech-texter-config
```

### Example `config/speeech-texter.php`:

```php
return [
    'api_key' => env('SPEEECH_TEXTER_X_API_KEY'),
    'voice_api' => env('SPEEECH_TEXTER_VOICE_API'),
    'prefix' => 'speeech-texter',
    'user_model' => env('SPEEECH_TEXTER_USER_MODEL', 'App\Models\User'),
    'file_model' => env('SPEEECH_TEXTER_FILE_MODEL', 'App\Models\File'),
];
```

## 🔧 Usage

### 1️⃣ Storing a Speech-to-Text Request
To store a new request, simply create a new `SpeeechTexter` model instance:

```php
use SpeeechTexter\Models\SpeeechTexter;

$record = SpeeechTexter::create([
    'user_id' => auth()->id(),
    'file_id' => $fileId,
    'file_url' => $fileUrl,
    'file' => $fileData,
    'result' => json_encode($transcriptionResult),
    'response_status_code' => 200,
]);
```

### 2️⃣ Retrieving Speech-to-Text Results
To get the transcribed result of a specific file:

```php
$record = SpeeechTexter::find($id);
echo $record->result;
```

### 3️⃣ Handling Speech-to-Text Requests in the Repository

```php
use SpeeechTexter\Repositories\SpeeechTexterRepository;

$speechTexterRepo = new SpeeechTexterRepository();
$response = $speechTexterRepo->speechToText($userId, $fileId, [
    'file' => $uploadedFile,
]);
```

### 4️⃣ Accessing Related Models

```php
$user = $record->user;
$file = $record->file;
```

### 5️⃣ Validating Inputs

```php
use SpeeechTexter\Validators\SpeeechTexterValidator;

$validator = new SpeeechTexterValidator();
$validator->validate($data);
```

## ⚙️ Service Provider
Make sure Laravel detects the service provider automatically:

```php
'providers' => [
    SpeeechTexter\Providers\SpeeechTexterServiceProvider::class,
],
```

## 🏗 Running Migrations

```bash
php artisan migrate
```

## 🏗 Queue Processing
To handle speech recognition requests asynchronously:

```bash
php artisan queue:work
```

## 🔒 Security
- Do not expose your API key in the frontend.
- Use environment variables for API keys and secrets.
- Ensure your file uploads are properly sanitized to prevent security risks.

## 📝 License
This package is open-source and licensed under the MIT License.

## 🤝 Contributing
We welcome contributions! Feel free to submit pull requests and report issues in the GitHub repository.

## 📞 Support
For support, please open an issue on GitHub or contact me personally via email at **amirmahdifor@gmail.com**.

