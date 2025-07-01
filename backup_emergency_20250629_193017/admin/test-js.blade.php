@extends('layouts.admin')

@section('title', 'Test JavaScript')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold text-white mb-4">Test JavaScript</h1>
    
    <button onclick="testFunction()" class="bg-blue-600 text-white px-4 py-2 rounded">
        Test Button
    </button>
    
    <div id="result" class="mt-4 text-white"></div>
</div>

<script>
function testFunction() {
    document.getElementById('result').innerHTML = '✅ JavaScript fonctionne !';
    console.log('Test function executed');
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded successfully');
});
</script>
@endsection
