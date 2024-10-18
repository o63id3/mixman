<script setup lang="ts" generic="TValues">
import { Button } from '@/Components/ui/button'
import { Card, CardContent, CardFooter, CardHeader } from '@/Components/ui/card'

import { useSubmit } from '@/Composables/submit'
import { FormContext, TypedSchema, useForm } from 'vee-validate'
import { Separator } from '@/Components/ui/separator'

import { type Method, VisitOptions } from '@inertiajs/core'
import { ResetIcon } from '@radix-icons/vue'

const props = withDefaults(
  defineProps<{
    formSchema: TypedSchema
    initialValues?: TValues
    route: string
    canUpdate?: boolean
    method?: Method
    options?: VisitOptions
  }>(),
  {
    canUpdate: true,
    method: 'patch',
  },
)

const emit = defineEmits(['success'])

const form = useForm({
  validationSchema: props.formSchema,
  initialValues: props.initialValues,
})

defineExpose<{
  form: FormContext
}>({
  form: form,
})

const { submit, loading, recentlySuccessful } = useSubmit(props.route, {
  method: props.method,
  preserveState: false,
  onSuccess: () => emit('success'),
  onError: (errors) => {
    form.setErrors(errors)

    if (props.options?.onError) props.options.onError(errors)
  },
})
const onSubmit = form.handleSubmit(submit)
</script>

<template>
  <form @submit="onSubmit">
    <Card>
      <CardHeader v-if="$slots.title">
        <slot name="title" />
      </CardHeader>
      <CardContent>
        <div
          class="grid grid-cols-1 gap-4 md:grid-cols-2"
          :class="{
            'pt-6': !$slots.title,
          }"
        >
          <slot :form="form" />
        </div>
      </CardContent>
      <CardFooter class="border-t px-6 py-4">
        <div class="flex items-center gap-4">
          <Button
            v-if="canUpdate"
            type="submit"
            :loading="loading"
            :disabled="
              loading || !form.meta.value.dirty || !form.meta.value.valid
            "
          >
            حفظ
          </Button>

          <Separator
            v-if="$slots.buttons && canUpdate"
            orientation="vertical"
            class="h-4"
          />

          <slot name="buttons" />
        </div>
      </CardFooter>
    </Card>
  </form>
</template>
