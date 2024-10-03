<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import { toast } from '@/Components/ui/toast'

import CreateFormLayout from '@/Components/forms/CreateFormLayout.vue'
import NetworkPartnersForm from './Partials/NetworkPartnersForm.vue'
import { Data, Network, User } from '@/types'

import { AlertCircle } from 'lucide-vue-next'
import { Alert, AlertDescription, AlertTitle } from '@/Components/ui/alert'

const props = defineProps<{
  network: Data<Network>
  partners: Array<User>
  remainingShare: number
}>()

const formSchema = toTypedSchema(
  z.object({
    user_id: z.string({ message: 'هذا الحقل مطلوب' }),
    share: z
      .number({ message: 'هذا الحقل مطلوب' })
      .min(1, { message: 'يجب أن يكون أكبر من 1' })
      .max(100, { message: 'يجب أن يكون أقل من 100' }),
  }),
)

const { handleSubmit, resetForm, setErrors } = useForm({
  validationSchema: formSchema,
})

const onSubmit = handleSubmit((values) => {
  router.post(route('network.partners.store', props.network.data.id), values, {
    preserveScroll: true,
    onSuccess: () => {
      toast({ title: 'تم إضافة الشريك' })
      resetForm()
    },
    onError: (errors) => setErrors(errors),
  })
})
</script>

<template>
  <Head title="Partners" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        إضافة شريك
        <span class="text-xs font-normal tracking-wide">
          ({{ network.data.name }})
        </span>
      </h2>
    </template>

    <Alert class="rounded-none sm:rounded-xl" variant="destructive">
      <AlertCircle class="h-4 w-4" />
      <AlertTitle>انتباه!</AlertTitle>
      <AlertDescription>
        <div v-if="remainingShare">
          نسبة الحصص المتبقية في
          <span class="font-bold">
            {{ network.data.name }}
          </span>
          هي
          <span class="font-bold">{{ Math.round(remainingShare) }}%</span>
        </div>
        <span v-else>لا يوجد حصص متبقية!</span>
      </AlertDescription>
    </Alert>

    <CreateFormLayout class="mt-2" @submit="onSubmit">
      <NetworkPartnersForm :partners="partners" />
    </CreateFormLayout>
  </AuthenticatedLayout>
</template>
