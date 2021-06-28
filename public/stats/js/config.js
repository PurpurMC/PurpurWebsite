const config = {
  type: 'line',
  options: {
    responsive: true,
    interaction: {
      mode: 'nearest',
      intersect: false
    },
    plugins: {
      title: {
        display: true,
        text: "Title",
        color: '#fff'
      },
      tooltip: {
        mode: 'index',
        intersect: false
      },
      legend: {
        display: true,
        labels: {
          color: '#fff'
        }
      }
    },
    scales: {
      x: {
        display: true,
        title: {
          display: true,
          text: "X",
          color: '#fff'
        },
        ticks: {
          color: '#fff' 
        },
        grid: {
          color: '#444'
        }
      },
      y: {
        display: true,
        beginAtZero: true,
        title: {
          display: true,
          text: "Y",
          color: '#fff'
        },
        ticks: {
          color: '#fff'
        },
        grid: {
          color: '#444'
        }
      }
    }
  }
};
