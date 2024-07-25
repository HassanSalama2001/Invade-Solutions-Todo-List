<template>
    <div class="container text-center">
        <h5>{{ title }}</h5>
        <span><h4 class="text-danger">*Hint*</h4> go to <b>Tasks</b> page to get the work done</span>
        <p class="m-5">&copy; {{ new Date().getFullYear() }} Hassan Mohamed. All rights reserved.</p>
    </div>
</template>

<script>
export default {
    setup: () => ({
        title: 'Welcome to the Todo Single Page Application in Laravel 11 and Vue JS 3'
    }),
    data() {
        return {
            currentYear: new Date().getFullYear(),
        };
    },
}
</script>
