<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { Head, router } from '@inertiajs/vue3'

import { useForm } from 'vee-validate'
import { toTypedSchema } from '@vee-validate/zod'
import * as z from 'zod'

import { toast } from '@/Components/ui/toast'
import { Region } from '@/types'
import UpdateFormLayout from '@/Components/forms/UpdateFormLayout.vue'
import RegionForm from './Partials/RegionForm.vue'
import DeleteLink from '@/Components/links/DeleteLink.vue'

const props = defineProps<{
  region: Region
  can: {
    delete: boolean
  }
}>()

const formSchema = toTypedSchema(
  z.object({
    name: z
      .string({ message: 'هذا الحقل مطلوب' })
      .min(2, { message: 'الاسم يجيب ان يكون حرفين على الاقل' }),
  }),
)

const { handleSubmit, setErrors } = useForm({
  validationSchema: formSchema,
  initialValues: {
    name: props.region.name,
  },
})

const onSubmit = handleSubmit((values) => {
  router.patch(route('regions.update', props.region.id), values, {
    preserveScroll: true,
    onSuccess: () => toast({ title: 'تم تعديل المنطقة' }),
    onError: (errors) => setErrors(errors),
  })
})
</script>

<template>
  <Head title="Regions" />

  <AuthenticatedLayout>
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800">
        {{ region.name }}
      </h2>
    </template>

    <UpdateFormLayout @submit="onSubmit" can-update>
      <template #buttons>
        <DeleteLink
          v-if="can.delete"
          :href="route('regions.destroy', region.id)"
          @success="toast({ title: 'تم حذف المنطقة' })"
        >
          حذف
        </DeleteLink>
      </template>

      <RegionForm />
    </UpdateFormLayout>
  </AuthenticatedLayout>
</template>
