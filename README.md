# SpeeechTexter Laravel Package

SpeeechTexter is a Laravel package that simplifies speech-to-text processing by integrating with external services. This package provides an easy way to store, manage, and retrieve transcribed audio files while allowing full flexibility in configuring dependencies such as user and file models.

## 🚀 Features
- Seamless speech-to-text integration
- Configurable API endpoints
- Dynamic model binding for `User` and `File`
- Supports Laravel migrations and service providers
- Queued job processing for speech recognition requests
- Storage support for temporary and persistent audio files
- Validation for user inputs and API requests

## 📦 Installation
You can install this package via Composer:
```bash
composer require caraxes/speeech-texter
```

After installation, publish the package configuration:
```bash
php artisan vendor:publish --tag=speeech-texter-config
```
This will create a `config/speeech-texter.php` file where you can define custom settings.

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

## 📌 Usage

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
You can call the `speechToText` method from the repository to send an audio file for transcription:
```php
use SpeeechTexter\Repositories\SpeeechTexterRepository;

$speechTexterRepo = new SpeeechTexterRepository();
$response = $speechTexterRepo->speechToText($userId, $fileId, [
    'file' => $uploadedFile, // Uploaded file instance
]);
```

### 4️⃣ Accessing Related Models
Since the package dynamically binds `User` and `File` models from the configuration, you can access them like this:
```php
$user = $record->user;
$file = $record->file;
```

### 5️⃣ Validating Inputs
The package includes validation logic to ensure that only valid files are processed. To customize validation, modify the `SpeeechTexterValidator` class:
```php
use SpeeechTexter\Validators\SpeeechTexterValidator;

$validator = new SpeeechTexterValidator();
$validator->validate($data);
```

## ⚙️ Service Provider
Make sure Laravel detects the service provider automatically in `config/app.php`:
```php
'providers' => [
    SpeeechTexter\Providers\SpeeechTexterServiceProvider::class,
],
```

## 🛠 Running Migrations
Run the following command to create necessary database tables:
```bash
php artisan migrate
```

## 🏗️ Queue Processing
To handle speech recognition requests asynchronously, make sure you have a queue worker running:
```bash
php artisan queue:work
```
This ensures that requests are processed in the background without affecting user experience.

## 🛡️ Security
- Do not expose your API key in the frontend.
- Use environment variables for API keys and secrets.
- Ensure your file uploads are properly sanitized to prevent security risks.

## 📝 License
This package is open-source and licensed under the MIT License.

## 🤝 Contributing
We welcome contributions! Feel free to submit pull requests and report issues in the GitHub repository.

## 📞 Support
For support, please open an issue on GitHub or reach out via email.

