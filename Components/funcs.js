function deleteLeverancier(id) {
			
				fetch('../Components/leverancier_delete.php', {
					method: 'POST',
					headers: {
						'Content-Type': 'application/x-www-form-urlencoded'
					},
					body: 'id=' + id
				})
				.then(response => response.json())
				.then(data => {
					if (data.success) {
						location.reload();
					} else {
						alert('Fout bij verwijderen: ' + data.message);
					}
				})
			}
		