python
import socket
import nmap

def scan_ports(ip):
    nm = nmap.PortScanner()
    nm.scan(ip, '1-1024')
    open_ports = []
    for host in nm.all_hosts():
        for proto in nm[host].all_protocols():
            lport = nm[host][proto].keys()
            for port in lport:
                if nm[host][proto][port]['state'] == 'open':
                    open_ports.append(port)
    return open_ports

def check_vulnerabilities(ip):
    nm = nmap.PortScanner()
    nm.scan(ip, '1-1024', '-sV --script vuln')
    vulnerabilities = []
    for host in nm.all_hosts():
        for proto in nm[host].all_protocols():
            lport = nm[host][proto].keys()
            for port in lport:
                if 'script' in nm[host][proto][port]:
                    for script in nm[host][proto][port]['script']:
                        if 'vuln' in script:
                            vulnerabilities.append(script)
    return vulnerabilities

# Получаем IP-адрес из файла
with open('data.txt', 'r') as file:
    for line in file:
        if line.startswith('Реальный IP:'):
            ip = line.split(': ')[1].strip()
            break

# Сканируем порты
open_ports = scan_ports(ip)
print("Открытые порты:", open_ports)

# Проверяем уязвимости
vulnerabilities = check_vulnerabilities(ip)
print("Уязвимости:", vulnerabilities)