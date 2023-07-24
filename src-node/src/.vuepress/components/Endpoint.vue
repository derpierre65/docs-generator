<template>
	<div>
		<div ref="heading-box"></div>
		<div class="flex flex-wrap w-full xl:flex-nowrap xl:space-x-4">
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
		document.querySelector('main.page').classList.add('page-layout-test');

		const heading = this.$el.querySelector('h1, h2, h3, h4, h5, h6');
		if (heading) {
			this.$refs['heading-box'].appendChild(heading);
		}
	},
	methods: {
		renderLayout() {
			const examples = this.$refs['cont-box'].querySelectorAll('.endpoint-example');
			if (examples) {
				examples.forEach((item) => {
					this.$refs['example-box'].appendChild(item);
				});
			}
		}
	}
};
</script>