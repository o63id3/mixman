<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import { toast } from '@/Components/ui/toast'
import { User } from '@/types'
import CreateFormLayout from '@/Components/forms/CreateFormLayout.vue'
import PaymentForm from './Partials/PaymentForm.vue'

const formSchema = toTypedSchema(
  z.object({
    seller_id: z.number({ message: 'هذا الحقل مطلوب' }),
    amount: z.number({ message: 'هذا الحقل مطلوب' }),
    notes: z.string().optional(),
  }),
)

const { handleSubmit, resetForm, setErrors, values, setFieldValue } = useForm({
  validationSchema: formSchema,
})

const onSubmit = handleSubmit((values) => {
  router.post(route('payments.store'), values, {
    preserveScroll: true,
    onSuccess: () => {
      toast({ title: 'تم إدخال الدفعة المالية' })
      resetForm()
    },
    onError: (errors) => setErrors(errors),
  })
})

defineProps<{
  sellers: Array<User>
}>()
</script>

<template>
  <Head title="Payments" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        دفعة مالية
      </h2>
    </template>

    <CreateFormLayout @submit="onSubmit">
      <PaymentForm
        :sellers="sellers"
        :selected="values.seller_id"
        @select="(selected: number) => setFieldValue('seller_id', selected)"
      />
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
