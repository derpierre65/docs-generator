<template>
	<div>
		<div class="header" ref="heading-box">
			<slot name="header" />
		</div>
		<div v-if="$slots.example || $slots.default" class="flex flex-wrap w-full xl:flex-nowrap xl:space-x-4">
			<div ref="cont-box" class="flex-auto">
				<slot />
			</div>
			<div ref="example-box" class="shrink-0 w-full xl:w-1/2">
				<slot name="example" />
			</div>
		</div>
	</div>
</template>

<script>
export default {
	name: 'Endpoint',
	expose: ['renderLayout'],
	mounted() {
		document.querySelector('main.page')?.classList.add('page-layout-test');
	},
};
</script>

<style lang="scss">
.header {
	> h1, > h2, > h3, > h4, > h5 {
		border-bottom: 1px solid var(--c-border);
	}
}
</style>