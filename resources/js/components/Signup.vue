<template>
    <div>
        <h2>Sign Up</h2>
        <form @submit.prevent="submitSignup">
            <div>
                <label for="name">Name</label>
                <input type="text" v-model="name" required />
            </div>
            <div>
                <label for="email">Email</label>
                <input type="email" v-model="email" required />
            </div>
            <div>
                <label for="password">Password</label>
                <input type="password" v-model="password" required />
            </div>
            <div>
                <label for="password_confirmation">Confirm Password</label>
                <input
                    type="password"
                    v-model="password_confirmation"
                    required
                />
            </div>
            <button type="submit">Sign Up</button>
        </form>

        <div v-if="errorMessage" class="error">{{ errorMessage }}</div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            name: "",
            email: "",
            password: "",
            password_confirmation: "",
            errorMessage: "",
        };
    },
    methods: {
        async submitSignup() {
            try {
                const response = await axios.post("/api/v1/sign-up", {
                    name: this.name,
                    email: this.email,
                    password: this.password,
                    password_confirmation: this.password_confirmation,
                });

                if (response.status === 201) {
                    // Handle success (e.g., redirect to login or home page)
                    this.$router.push("/login");
                }
            } catch (error) {
                if (error.response) {
                    this.errorMessage =
                        error.response.data.message || "Signup failed!";
                } else {
                    this.errorMessage = "Network error. Please try again.";
                }
            }
        },
    },
};
</script>

<style scoped>
.error {
    color: red;
}
</style>
