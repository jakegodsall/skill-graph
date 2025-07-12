import { Head, router } from '@inertiajs/react';
import React, { act, useCallback, useEffect, useState } from 'react';
import {
    addEdge,
    Background,
    BackgroundVariant,
    Connection,
    Controls,
    Edge,
    MiniMap,
    Node,
    NodeTypes,
    ReactFlow,
    useEdgesState,
    useNodesState,
} from 'reactflow';
import 'reactflow/dist/style.css';

import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import AppLayout from '@/layouts/app-layout';
import { Award, BookOpen, Code, FileText, Plus, Settings, Target, Zap } from 'lucide-react';

import BookNode from '@/components/flow-nodes/book-node';
import CertificationNode from '@/components/flow-nodes/certification-node';
import CourseNode from '@/components/flow-nodes/course-node';
import OtherNode from '@/components/flow-nodes/other-node';
import PracticeNode from '@/components/flow-nodes/practice-node';
import ProjectNode from '@/components/flow-nodes/project-node';
import SkillNode from '@/components/flow-nodes/skill-node';

const nodeTypes: NodeTypes = {
    skill: SkillNode,
    course: CourseNode,
    project: ProjectNode,
    book: BookNode,
    practice: PracticeNode,
    certification: CertificationNode,
    other: OtherNode,
};

const getNodeIcon = (type: Activity['type']) => {
    switch (type) {
        case 'course':
            return <BookOpen className="h-4 w-4" />;
        case 'project':
            return <Code className="h-4 w-4" />;
        case 'book':
            return <FileText className="h-4 w-4" />;
        case 'practice':
            return <Target className="h-4 w-4" />;
        case 'certification':
            return <Award className="h-4 w-4" />;
        case 'other':
            return <Zap className="h-4 w-4" />;
    }
};

const getStatusColor = (status: Activity['status']) => {
    switch (status) {
        case 'not_started':
            return 'bg-gray-100 text-gray-800';
        case 'in_progress':
            return 'bg-blue-100 text-blue-800';
        case 'completed':
            return 'bg-green-100 text-green-800';
        case 'paused':
            return 'bg-yellow-100 text-yellow-800';
    }
};

export default function SkillGraph() {
    const [selectedSkill, setSelectedSkill] = useState<string>('all');
    const [skills, setSkills] = useState<Skill[]>([]);
    const [activities, setActivities] = useState<Activity[]>([]);

    const [nodes, setNodes, onNodesChange] = useNodesState([]);
    const [edges, setEdges, onEdgesChange] = useEdgesState([]);

    const [loading, setLoading] = useState<boolean>(true);

    // Fetch skills
    const fetchSkills = useCallback(async () => {
        try {
            const response = await fetch('/api/skills');
            const data = await response.json();
            setSkills(data);
        } catch (error) {
            console.error('Error fetching skills:', error);
        }
    }, []);

    const fetchActivities = useCallback(async () => {
        try {
            const url = selectedSkill === 'all' ? '/api/activities' : `/api/activities?skill_id=${selectedSkill}`;
            const response = await fetch(url);
            const data = await response.json();
            setActivities(data);
        } catch (error) {
            console.error('Error fetching activities:', error);
        }
    }, [selectedSkill]);

    const convertToNodesAndEdges = useCallback(() => {
        const uniqueSkills = Array.from(new Map(activities.map((activity) => [activity.skill_id, activity.skill])).values());

        const skillNodes: Node[] = uniqueSkills.map((skill, index) => {
            const skillActivities = activities.filter((activity) => activity.skill_id === skill.id);

            return {
                id: `skill-${skill.id}`,
                type: 'skill',
                position: {
                    x: 50,
                    y: 50 + index * 250,
                },
                data: {
                    ...skill,
                    activities_count: skillActivities.length,
                },
            };
        });

        const activityNodes: Node[] = activities.map((activity) => {
            const skillIndex = uniqueSkills.findIndex((skill) => skill.id === activity.skill_id);

            return {
                id: activity.id.toString(),
                type: activity.type,
                position: {
                    x: activity.position_x || 300 + Math.random() * 500,
                    y: activity.position_y || 50 + skillIndex * 250 + Math.random() * 200,
                },
                data: {
                    ...activity,
                    label: activity.name,
                    icon: getNodeIcon(activity.type),
                    statusColor: getStatusColor(activity.status),
                },
            };
        });

        const newEdges: Edge[] = [];

        activities.forEach((activity) => {
            activity.depends_on.forEach((dependency) => {
                newEdges.push({
                    id: `${dependency.id}-${activity.id}`,
                    source: dependency.id.toString(),
                    target: activity.id.toString(),
                    type: 'smoothstep',
                    animated: activity.status === 'in_progress',
                    style: { stroke: activity.skill.color },
                });
            });
        });

        activities.forEach((activity) => {
            if (activity.depends_on.length === 0) {
                newEdges.push({
                    id: `skill-${activity.skill_id}-${activity.id}`,
                    source: `skill-${activity.skill_id}`,
                    target: activity.id.toString(),
                    type: 'smoothstep',
                    animated: activity.status === 'in_progress',
                    style: {
                        stroke: activity.skill.color,
                        strokeWidth: 3,
                    },
                });
            }
        });

        console.log(skillNodes, activityNodes, newEdges);

        setNodes([...skillNodes, ...activityNodes]);
        setEdges(newEdges);
    }, [activities, setNodes, setEdges]);

    const onNodeDragStop = useCallback(async (event: React.MouseEvent, node: Node) => {
        let entity = '';
      
        if (node.id.startsWith('skill-')) {
            entity = 'skills';
        } else {
          entity = 'activities';
        }

        try {
            await fetch(`/api/${entity}/${node.id}/position`, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                },
                body: JSON.stringify({
                    position_x: node.position.x,
                    position_y: node.position.y,
                }),
            });
        } catch (error) {
            console.error('Error saving node position:', error);
        }

        console.log(activities, edges);
    }, [activities, edges]);

    useEffect(() => {
        const loadData = async () => {
            setLoading(true);
            await fetchSkills();
            await fetchActivities();
            setLoading(false);
        };
        loadData();
    }, [fetchSkills, fetchActivities]);

    useEffect(() => {
        convertToNodesAndEdges();
    }, [convertToNodesAndEdges]);

    const onConnect = useCallback((params: Edge | Connection) => setEdges((eds) => addEdge(params, eds)), [setEdges]);

    if (loading) {
        return (
            <AppLayout>
                <Head title="Skill Graph" />
                <div className="flex h-64 items-center justify-center">
                    <div className="h-32 w-32 animate-spin rounded-full border-b-2 border-blue-600"></div>
                </div>
            </AppLayout>
        );
    }

    return (
        <AppLayout>
            <Head title="Skill Graph" />

            <div className="flex h-[calc(100vh-theme(spacing.16))] flex-col space-y-6">
                {/* Header */}
                <div className="flex flex-shrink-0 items-center justify-between">
                    <div>
                        <h1 className="text-2xl font-bold text-gray-900">Skill Graph</h1>
                        <p className="text-gray-600">Visualize your learning journey and skill dependencies</p>
                    </div>
                    <div className="flex items-center space-x-4">
                        <Select value={selectedSkill} onValueChange={setSelectedSkill}>
                            <SelectTrigger className="w-48">
                                <SelectValue placeholder="Select skill" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="all">All Skills</SelectItem>
                                {skills.map((skill) => (
                                    <SelectItem key={skill.id} value={skill.id.toString()}>
                                        <div className="flex items-center space-x-2">
                                            <div className="h-3 w-3 rounded-full" style={{ backgroundColor: skill.color }} />
                                            <span>{skill.name}</span>
                                        </div>
                                    </SelectItem>
                                ))}
                            </SelectContent>
                        </Select>
                        <Button onClick={() => router.visit('/skills')}>
                            <Settings className="mr-2 h-4 w-4" />
                            Manage Skills
                        </Button>
                        <Button onClick={() => router.visit('/activities')}>
                            <Plus className="mr-2 h-4 w-4" />
                            Manage Activities
                        </Button>
                    </div>
                </div>

                {/* Skills Overview */}
                <div className="grid flex-shrink-0 grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                    {skills.map((skill) => (
                        <Card key={skill.id} className="cursor-pointer transition-shadow hover:shadow-md">
                            <CardHeader className="pb-3">
                                <div className="flex items-center justify-between">
                                    <div className="h-4 w-4 rounded-full" style={{ backgroundColor: skill.color }} />
                                    <Badge variant="secondary">{skill.activities_count}</Badge>
                                </div>
                                <CardTitle className="text-lg">{skill.name}</CardTitle>
                            </CardHeader>
                            {skill.description && (
                                <CardContent className="pt-0">
                                    <p className="text-sm text-gray-600">{skill.description}</p>
                                </CardContent>
                            )}
                        </Card>
                    ))}
                </div>

                {/* React Flow Graph */}
                <Card className="min-h-0 flex-1">
                    <CardContent className="h-full p-0">
                        <ReactFlow
                            nodes={nodes}
                            edges={edges}
                            onNodesChange={onNodesChange}
                            onEdgesChange={onEdgesChange}
                            onConnect={onConnect}
                            onNodeDragStop={onNodeDragStop}
                            nodeTypes={nodeTypes}
                            fitView
                            attributionPosition="bottom-left"
                        >
                            <Controls />
                            <Background variant={BackgroundVariant.Dots} gap={12} size={1} />
                        </ReactFlow>
                    </CardContent>
                </Card>
            </div>
        </AppLayout>
    );
}
