<script setup lang="ts">
import InputError from '@/Components/InputError.vue'
import Button from '@/Components/ui/button/Button.vue'
import Input from '@/Components/ui/input/Input.vue'
import Label from '@/Components/ui/label/Label.vue'
import {
  Card,
  CardContent,
  CardDescription,
  CardFooter,
  CardHeader,
  CardTitle,
} from '@/Components/ui/card'

import { useForm, usePage } from '@inertiajs/vue3'

const user = usePage().props.auth.user

const form = useForm({
  name: user.name,
  username: user.username,
})
</script>

<template>
  <section>
    <Card>
      <CardHeader>
        <CardTitle>معلومات الحساب</CardTitle>
        <CardDescription> حدث اسمك واسم المستخدم الخاص بك </CardDescription>
      </CardHeader>
      <CardContent>
        <form class="max-w-xl space-y-6">
          <div>
            <Label for="name">اسمك</Label>

            <Input
              id="name"
              type="text"
              class="mt-2 block w-full"
              v-model="form.name"
              autofocus
            />

            <InputError class="mt-2" :message="form.errors.name" />
          </div>

          <div>
            <Label for="username">اسم المستخدم</Label>

            <Input
              id="username"
              type="text"
              class="mt-2 block w-full"
              autocapitalize="none"
              v-model="form.username"
            />

            <InputError class="mt-2" :message="form.errors.username" />
          </div>
        </form>
      </CardContent>
      <CardFooter class="border-t px-6 py-4">
        <div class="flex items-center gap-4">
          <Button
            @click="form.patch(route('profile.update'))"
            :disabled="form.processing"
          >
            حفظ
          </Button>

          <Transition
            enter-active-class="transition ease-in-out"
            enter-from-class="opacity-0"
            leave-active-class="transition ease-in-out"
            leave-to-class="opacity-0"
          >
            <p v-if="form.recentlySuccessful" class="text-sm text-gray-600">
              تم الحفظ.
            </p>
          </Transition>
        </div>
      </CardFooter>
    </Card>
  </section>
</template>
