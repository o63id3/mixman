<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import { toast } from '@/Components/ui/toast'

import CreateFormLayout from '@/Components/forms/CreateFormLayout.vue'
import SellerForm from './Partials/SellerForm.vue'
import { Region } from '@/types'

const formSchema = toTypedSchema(
  z.object({
    region: z.string({ message: 'هذا الحقل مطلوب' }),
    name: z
      .string({ message: 'هذا الحقل مطلوب' })
      .min(2, { message: 'الاسم يجيب ان يكون حرفين على الاقل' }),
    username: z
      .string({ message: 'هذا الحقل مطلوب' })
      .min(2, { message: 'اسم المستخدم يجيب ان يكون حرفين على الاقل' }),
    password: z
      .string({ message: 'هذا الحقل مطلوب' })
      .min(4, { message: 'كلمة المرور يجب ان تكون 4 احرف على الاقل' }),
    contact_info: z.string().optional(),
    notes: z.string().optional(),
  }),
)

const { handleSubmit, resetForm, setErrors } = useForm({
  validationSchema: formSchema,
})

const onSubmit = handleSubmit((values) => {
  router.post(route('sellers.store'), values, {
    preserveScroll: true,
    onSuccess: () => {
      toast({ title: 'تم إنشاء نقطة البيع' })
      resetForm()
    },
    onError: (errors) => setErrors(errors),
  })
})

defineProps<{
  regions: Array<Region>
}>()
</script>

<template>
  <Head title="Sellers" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        نقطة بيع جديدة
      </h2>
    </template>

    <CreateFormLayout @submit="onSubmit">
      <SellerForm :regions="regions" />
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
