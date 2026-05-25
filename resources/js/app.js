document.addEventListener('click', (event) => {
	const target = event.target instanceof Element ? event.target : null;

	if (!target) {
		return;
	}

	const actionTrigger = target.closest('[data-action-menu-trigger]');
	const openActionMenu = document.querySelector('[data-action-menu-panel][data-open="true"]');

	if (actionTrigger) {
		event.preventDefault();

		const menuId = actionTrigger.getAttribute('data-action-menu-trigger');
		const menu = menuId ? document.getElementById(menuId) : null;

		if (menu instanceof HTMLElement) {
			if (openActionMenu && openActionMenu !== menu) {
				openActionMenu.dataset.open = 'false';
				openActionMenu.style.display = 'none';
			}

			const isOpen = menu.dataset.open === 'true';
			if (isOpen) {
				menu.dataset.open = 'false';
				menu.style.display = 'none';
				return;
			}

			menu.style.display = 'block';
			const rect = actionTrigger.getBoundingClientRect();
			const menuRect = menu.getBoundingClientRect();
			const top = Math.min(window.innerHeight - menuRect.height - 16, rect.bottom + 8);
			const left = Math.max(16, Math.min(rect.right - menuRect.width, window.innerWidth - menuRect.width - 16));
			menu.style.top = `${Math.max(16, top)}px`;
			menu.style.left = `${left}px`;
			menu.dataset.open = 'true';
			return;
		}
	}

	if (openActionMenu) {
		const clickedMenu = target.closest('[data-action-menu-panel]');
		if (!clickedMenu) {
			openActionMenu.dataset.open = 'false';
			openActionMenu.style.display = 'none';
		}
	}

	const openTrigger = target.closest('[data-open-dialog]');

	if (openTrigger) {
		event.preventDefault();

		const dialog = document.getElementById(openTrigger.dataset.openDialog);

		if (dialog instanceof HTMLDialogElement && !dialog.open) {
			dialog.showModal();
		}
	}

	const closeTrigger = target.closest('[data-close-dialog]');

	if (closeTrigger) {
		const dialog = closeTrigger.closest('dialog');

		if (dialog instanceof HTMLDialogElement) {
			dialog.close();
		}
	}
});

document.addEventListener('click', (event) => {
	if (event.target instanceof HTMLDialogElement && event.target.open) {
		event.target.close();
	}
});
